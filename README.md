# php-observer-client
A client for the Observer-Server

Example for PHP-DI:

```
ObserverEndPointClient::class => object(ObserverCurlClient::class)
    ->constructorParameter('authToken', link('observer.token')),

ObserverClient::class => object()
    ->constructorParameter('endpointUrl', link('observer.url')),

ObserverProject::class => object()
    ->constructorParameter('key', link('observer.project')),
```