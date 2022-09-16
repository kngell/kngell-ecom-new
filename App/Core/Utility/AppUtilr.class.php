<?php

declare(strict_types=1);

final class AppUtil
{
    public function singleton()
    {
        return [
            'GlobalVariablesInterface' => GlobalVariables::class,
            'GlobalManagerInterface' => GlobalManager::class,
            'SessionEnvironment' => SessionEnvironment::class,
            'SessionStorageInterface' => NativeSessionStorage::class,
            'SessionInterface' => Session::class,
            'ResponseHandler' => ResponseHandler::class,
            'RequestHandler' => RequestHandler::class,
            'Token' => Token::class,
            'Sanitizer' => Sanitizer::class,
            'MoneyManager' => MoneyManager::class,
            'RooterInterface' => Rooter::class,
            'CookieStoreInterface' => NativeCookieStore::class,
            'CookieInterface' => Cookie::class,
            'CacheEnvironmentConfigurations' => CacheEnvironmentConfigurations::class,
            'CacheStorageInterface' => NativeCacheStorage::class,
            'CacheInterface' => Cache::class,
            'LoggerHandlerInterface' => NativeLoggerHandler::class,
            'LoggerInterface' => Logger::class,
            'LoggerFactory' => LoggerFactory::class,
            'LoginForm' => LoginForm::class,
            'RegisterForm' => RegisterForm::class,
            'DispatcherInterface' => Dispatcher::class,
            'MailerInterface' => Mailer::class,
            'DataMapperEnvironmentConfig' => DataMapperEnvironmentConfig::class,
            'DataMapperInterface' => DataMapper::class,
            'QueryBuilderInterface' => QueryBuilder::class,
            'DatabaseConnexionInterface' => DatabaseConnexion::class,
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

    public function dataAccessLayerClass()
    {
        return[
            'EntityManagerInterface' => EntityManager::class,
            'RepositoryInterface' => Repository::class,
            'DataAccessLayerManager' => DataAccessLayerManager::class,
            'CrudInterface' => Crud::class,
        ];
    }

    public function bindedClass()
    {
        return [
            'QueryParamsInterface' => QueryParams::class,
            'View' => View::class,
            'CommentsInterface' => Comments::class,
            'ClientFormBuilder' => ClientFormBuilder::class,
            'DisplayPhonesInterface' => PhonesHomePage::class,
        ];
    }
}
