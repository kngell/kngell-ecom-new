<?php

declare(strict_types=1);

class Container implements ContainerInterface
{
    use ContainerGettersAndSettersTrait;

    /** @var ContainerInterface */
    protected static $instance;

    /** @var array */
    protected array $services = [];

    /** @var array */
    protected array $bindings = [];

    /**
     * All of the registered rebound callbacks.
     *
     * @var array
     */
    protected $reboundCallbacks = [];

    private function __construct()
    {
    }

    /**
     * Get container instance
     * ===============================================.
     * @return mixed
     */
    public static function getInstance() : mixed
    {
        if (! isset(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * Make object
     * ===============================================.
     * @param string $abstract
     * @param array $args
     * @return mixed
     */
    public function make(string|array $abstract, array $args = []) : mixed
    {
        if ($this->has($abstract)) {
            return $this->services[$abstract];
        }
        if (isset($this->bindings[$abstract]) && is_array($this->bindings[$abstract]) && ! empty($this->bindings[$abstract]['args'])) {
            $args = $this->bindings[$abstract]['args'];
        }
        $concrete = $this->bindings[$abstract]['concrete'] ?? $abstract;
        if ($concrete instanceof Closure || $concrete === $abstract) {
            $object = $this->resolve($concrete, $args);
        } else {
            $object = $this->make($concrete, $args);
        }
        if (isset($this->bindings[$abstract]) && $this->bindings[$abstract]['shared']) {
            $this->services[$abstract] = $object;
        }
        return $object;
    }

    public function bind(string $abstract, Closure | string | null $concrete = null, bool $shared = false, array $args = []): self
    {
        $this->bindings[$abstract] = [
            'concrete' => $concrete,
            'shared' => $shared,
            'args' => $args,
        ];
        return $this;
    }

    public function singleton(string $abstract, Closure | string | null $concrete = null): self
    {
        return $this->bind($abstract, $concrete, true);
    }

    /**
     * Get Objetct.
     * ================================================.
     * @param string $id
     * @return mixed
     */
    public function get(string $id) : mixed
    {
        if (! $this->has($id)) {
            throw new ComponentNotFoundException(sprintf('Could not found Component name : %s', $id));
        }
        return $this->services[$id];
    }

    public static function setInstance(?Application $container = null)
    {
        return static::$instance = $container;
    }

    /**
     * Check is Container as singleton
     *  ==============================================.
     * @param string $id Identifier of the entry to look for.
     * @return bool
     */
    public function has(string $id): bool
    {
        return array_key_exists($id, $this->services) && isset($this->services[$id]);
    }

    /**
     * Register an existing instance as shared in the container.
     *
     * @param  string  $abstract
     * @param  mixed  $instance
     * @return mixed
     */
    public function instance(string $abstract, mixed $instance) : mixed
    {
        $isBound = $this->isBound($abstract);
        $this->services[$abstract] = $instance;
        if ($isBound) {
            $this->rebound($abstract);
        }
        return $instance;
    }

    /**
     * Determine if the given abstract type has been bound.
     * ========================================================.
     * @param  string  $abstract
     * @return bool
     */
    public function isBound($abstract)
    {
        return isset($this->bindings[$abstract]) ||
               isset($this->services[$abstract]);
    }

    public function flush(): void
    {
        $this->bindings = [];
        $this->services = [];
    }

    /**
     * Fire the "rebound" callbacks for the given abstract type.
     *
     * @param  string  $abstract
     * @return void
     */
    protected function rebound($abstract)
    {
        $instance = $this->make($abstract);
        foreach ($this->getReboundCallbacks($abstract) as $callback) {
            call_user_func($callback, $this, $instance);
        }
    }

    /**
     * Get the rebound callbacks for a given type.
     *
     * @param  string  $abstract
     * @return array
     */
    protected function getReboundCallbacks($abstract)
    {
        return $this->reboundCallbacks[$abstract] ?? [];
    }

    /**
     * Build Objects
     * =========================================================================================.
     * @param Closure|string $concrete
     * @return mixed
     */
    private function resolve(Closure | string $concrete, array $args = []): mixed
    {
        if ($concrete instanceof Closure) {
            return $concrete($this);
        }
        try {
            /** @var ReflectionClass */
            $reflector = new ReflectionClass($concrete);
        } catch (ReflectionException $e) {
            throw new BindingResolutionException("Target class [$concrete] does not exist.", 0, $e->getCode());
        }
        if (! $reflector->isInstantiable()) {
            throw new BindingResolutionException("Target [$concrete] is not instantiable.");
        }
        $constructor = $reflector->getConstructor();
        if ($constructor === null) {
            return $this->objectWithContainer($reflector->newInstance(), $reflector);
        }
        $classDependencies = $this->resolveClassDependencies(
            $constructor->getParameters(),
            $args,
            $reflector
        );
        return $this->objectWithContainer($reflector->newInstanceArgs($classDependencies), $reflector);
    }

    /**
     * Resolve Dependencies
     * =========================================================================================.
     * @param array $dependencies
     * @return array
     */
    private function resolveClassDependencies(array $reflectionDependencies, array $args, reflectionClass $reflector): array
    {
        $classDependencies = [];
        /** @var ReflectionParameter $dependency */
        foreach ($reflectionDependencies as $key => $dependency) {
            $serviceType = $dependency->getType(); // ReflectionType|null
            if (! $serviceType instanceof ReflectionNamedType || $serviceType->isBuiltin()) {
                if ($dependency->isDefaultValueAvailable() || ! empty($args)) {
                    if ($dependency->isDefaultValueAvailable() && ! array_key_exists($dependency->name, $args)) {
                        $classDependencies[] = $dependency->getDefaultValue();
                    }
                    if (array_key_exists($dependency->name, $args)) {
                        $classDependencies[] = $args[$dependency->name];
                    } elseif (array_key_exists($key, $args)) {
                        $classDependencies[] = $args[$key];
                    } elseif (! empty($args)) {
                        $classDependencies[] = $args;
                    }
                } else {
                    throw new DependencyHasNoDefaultValueException('Sorry cannot resolve class dependency ' . $dependency->name);
                }
            } elseif (! $reflector->isUserDefined()) {
                $classDependencies[] = $this->make($serviceType->getName());
            } else {
                if (array_key_exists($dependency->name, $args)) {
                    $classDependencies[] = $args[$dependency->name];
                } elseif (array_key_exists($key, $args)) {
                    $classDependencies[] = $args[$key];
                } elseif (($dependency->isOptional() || $dependency->isDefaultValueAvailable()) && empty($args) && (! class_exists($serviceType->getName()))) {
                    $classDependencies[] = $dependency->getDefaultValue();
                } else {
                    $classDependencies[] = $this->make($serviceType->getName()); //dependency->name;
                }
            }
        }
        return $classDependencies;
    }

    /**
     * Set Container into created object
     * =========================================================================================.
     * @param ReflectionClass $reflector
     * @param object $obj
     * @return void
     */
    private function objectWithContainer(Object $obj, $reflector)
    {
        if ($reflector->hasProperty('container')) {
            $reflectionContainer = $reflector->getProperty('container');
            $reflectionContainer->setAccessible(true);
            if (! $reflectionContainer->isInitialized($obj)) {
                $reflectionContainer->setValue($obj, $this);
            }
        }
        return $obj;
    }
}
