Pusher Notifier
==============

Provides [Pusher](https://pusher.com) integration for Symfony Notifier.

DSN example
-----------

```
PUSHER_DSN=pusher://APP_KEY:APP_SECRET@APP_ID:SERVER?channel=CHANNEL
```

where:
 - `APP_KEY` is your app unique key
 - `APP_SECRET` is your app unique and secret password
 - `APP_ID` is your app unique id
 - `SERVER` is your app unique id

valid DSN's are:
```
PUSHER_DSN=pusher://as8d09a0ds8:as8d09a8sd0a8sd0@123123123:M1?channel=my-channel-name
```

invalid DSN's are:
```
PUSHER_DSN=pusher://asdasdasd@asdasdasd?channel=my-channel-name
PUSHER_DSN=pusher://:asdasdasd@asdasdasd?channel=my-channel-name
PUSHER_DSN=pusher://asdadasdasd:asdasdasd@asdasdasd?channel=#my-channel-name
```
