<?php

declare(strict_types=1);

final class ContainerAliasses
{
    public static function get() : array
    {
        return[
            'singleton' => self::singleton(),
            'bind' => array_merge(self::dataAccessLayerClass(), self::bindedClass()),
        ];
    }

    private static function bindedClass()
    {
        return [
            'QueryParamsInterface' => QueryParams::class,
            'View' => View::class,
            'CommentsInterface' => Comments::class,
            'ClientFormBuilder' => ClientFormBuilder::class,
            'DisplayPhonesInterface' => PhonesHomePage::class,
            'FilesManagerInterface' => ImageManager::class,
            'MoneyManager' => MoneyManager::class,
            'FilesSystemInterface' => FileSystem::class,
            'UploaderInterface' => Uploader::class,
            'EventDispatcherInterface' => EventDispatcher::class,
            'ListenerProviderInterface' => ListenerProvider::class,
            'TreeBuilderInterface' => TreeBuilder::class,
            'CollectionInterface' => Collection::class,
            'ContainerInterface' => Container::class,
            'CustomReflectorInterface' => CustomReflector::class,
            'FormBuilder' => FormBuilder::class,
            'PaymentGatewayInterface' => PaymentServicesFactory::class,
        ];
    }

    private static function dataAccessLayerClass()
    {
        return[
            'EntityManagerInterface' => EntityManager::class,
            'RepositoryInterface' => Repository::class,
            'DataAccessLayerManager' => DataAccessLayerManager::class,
            'CrudInterface' => Crud::class,
            'MailerInterface' => Mailer::class,
            'DataMapperEnvironmentConfig' => DataMapperEnvironmentConfig::class,
            'DataMapperInterface' => DataMapper::class,
            'QueryBuilderInterface' => QueryBuilder::class,
        ];
    }

    private static function singleton() : array
    {
        return [
            'RooterInterface' => Rooter::class,
            'GlobalVariablesInterface' => GlobalVariables::class,
            'GlobalManagerInterface' => GlobalManager::class,
            'SessionEnvironment' => SessionEnvironment::class,
            'SessionStorageInterface' => NativeSessionStorage::class,
            'SessionInterface' => Session::class,
            'ResponseHandler' => ResponseHandler::class,
            'RequestHandler' => RequestHandler::class,
            'Token' => Token::class,
            'Sanitizer' => Sanitizer::class,
            'CacheEnvironmentConfigurations' => CacheEnvironmentConfigurations::class,
            'CacheStorageInterface' => NativeCacheStorage::class,
            'CacheInterface' => Cache::class,
            'CookieStoreInterface' => NativeCookieStore::class,
            'CookieInterface' => Cookie::class,
            'LoggerHandlerInterface' => NativeLoggerHandler::class,
            'LoggerInterface' => Logger::class,
            'LoggerFactory' => LoggerFactory::class,
            'LoginForm' => LoginForm::class,
            'RegisterForm' => RegisterForm::class,
            'DispatcherInterface' => Dispatcher::class,
            'DatabaseConnexionInterface' => DatabaseConnexion::class,
        ];
    }
}