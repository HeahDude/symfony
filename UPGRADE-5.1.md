UPGRADE FROM 5.0 to 5.1
=======================

Console
-------

 * `Command::setHidden()` is final since Symfony 5.1

Dotenv
------

 * Deprecated passing `$usePutenv` argument to Dotenv's constructor, use `Dotenv::usePutenv()` instead.

EventDispatcher
---------------

 * Deprecated `LegacyEventDispatcherProxy`. Use the event dispatcher without the proxy.

Form
----

 * Implementing the `FormConfigInterface` without implementing the `getIsEmptyCallback()` method
   is deprecated. The method will be added to the interface in 6.0.
 * Implementing the `FormConfigBuilderInterface` without implementing the `setIsEmptyCallback()` method
   is deprecated. The method will be added to the interface in 6.0.
 * Added argument `callable|null $filter` to `ChoiceListFactoryInterface::createListFromChoices()` and `createListFromLoader()` - not defining them is deprecated.
 * Usage of `choice_attr` option as an array of nested arrays has been deprecated and indexes will be considered as attributes in 6.0. Use a unique array for all choices or a `callable` instead.

   Before:
   ```php
   // Single array for all choices using callable
   'choice_attr' => function () {
       return ['class' => 'choice-options'];
   },

   // Different arrays per choice using array
   'choices' => [
       'Yes' => true,
       'No' => false,
       'Maybe' => null,
   ],
   'choice_attr' => [
       'Yes' => ['class' => 'option-green'],
       'No' => ['class' => 'option-red'],
   ],
   ```

   After:
   ```php
   // Single array for all choices using array
   'choice_attr' => ['class' => 'choice-options'],

   // Different arrays per choice using callable
   'choices' => [
       'Yes' => true,
       'No' => false,
       'Maybe' => null,
   ],
   'choice_attr' => function ($choice, $index, $value) {
       if ('Yes' === $index) {
           return ['class' => 'option-green'];
       }
       if ('No' === $index) {
           return ['class' => 'option-red'];
       }

       return [];
   },
   ```

FrameworkBundle
---------------

 * Deprecated passing a `RouteCollectionBuilder` to `MicroKernelTrait::configureRoutes()`, type-hint `RoutingConfigurator` instead
 * Deprecated *not* setting the "framework.router.utf8" configuration option as it will default to `true` in Symfony 6.0

HttpFoundation
--------------

 * Deprecate `Response::create()`, `JsonResponse::create()`,
   `RedirectResponse::create()`, and `StreamedResponse::create()` methods (use
   `__construct()` instead)
 * Made the Mime component an optional dependency

Mailer
------

 * Deprecated passing Mailgun headers without their "h:" prefix.

Messenger
---------

 * Deprecated AmqpExt transport. It has moved to a separate package. Run `composer require symfony/amqp-messenger` to use the new classes.
 * Deprecated Doctrine transport. It has moved to a separate package. Run `composer require symfony/doctrine-messenger` to use the new classes.
 * Deprecated RedisExt transport. It has moved to a separate package. Run `composer require symfony/redis-messenger` to use the new classes.
 * Deprecated use of invalid options in Redis and AMQP connections.

Notifier
--------

 * [BC BREAK] The `ChatMessage::fromNotification()` method's `$recipient` and `$transport`
   arguments were removed.
 * [BC BREAK] The `EmailMessage::fromNotification()` and `SmsMessage::fromNotification()`
   methods' `$transport` argument was removed.

PhpUnitBridge
-------------

 * Deprecated the `@expectedDeprecation` annotation, use the `ExpectDeprecationTrait::expectDeprecation()` method instead.

Routing
-------

 * Deprecated `RouteCollectionBuilder` in favor of `RoutingConfigurator`.
 * Added argument `$priority` to `RouteCollection::add()`
 * Deprecated the `RouteCompiler::REGEX_DELIMITER` constant

Security
--------

 * Deprecated `ROLE_PREVIOUS_ADMIN` role in favor of `IS_IMPERSONATOR` attribute.

   *before*
   ```twig
   {% if is_granted('ROLE_PREVIOUS_ADMIN') %}
       <a href="">Exit impersonation</a>
   {% endif %}
   ```

   *after*
   ```twig
   {% if is_granted('IS_IMPERSONATOR') %}
       <a href="">Exit impersonation</a>
   {% endif %}
   ```

Yaml
----

 * Deprecated using the `!php/object` and `!php/const` tags without a value.
