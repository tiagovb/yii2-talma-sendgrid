yii2-sendgrid
=============
Sendgrid Mailer for Yii2

> based on [shershennm/yii2-sendgrid](https://github.com/shershennm/yii2-sendgrid.git)

[![Latest Stable Version](https://poser.pugx.org/thiagotalma/yii2-sendgrid/v/stable.png)](https://packagist.org/packages/thiagotalma/yii2-sendgrid)
[![Total Downloads](https://poser.pugx.org/thiagotalma/yii2-sendgrid/downloads.png)](https://packagist.org/packages/thiagotalma/yii2-sendgrid)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist thiagotalma/yii2-sendgrid "*"
```

or add

```
"thiagotalma/yii2-sendgrid": "*"
```

to the require section of your `composer.json` file.

Usage
-----
To use Mailer, you should configure it in the application configuration like the following:

Usign API Key:
```php
'components' => [
    ...
    'mailer' => [
        'class' => 'thiagotalma\sendgrid\Mailer',
        'key' => 'your api key',
        //'viewPath' => '@app/views/mail', // your view path here
    ],
    ...
],
```

Usign username and password:
```php
'components' => [
    ...
    'mailer' => [
        'class' => 'thiagotalma\sendgrid\Mailer',
        'username' => 'your username',
        'password' => 'your password here',
        //'viewPath' => '@app/views/mail', // your view path here
    ],
    ...
],
```

To send an email, you may use the following code:
```php
$sendGrid = Yii::$app->mailer;
$message = $sendGrid->compose('contact/html', ['contactForm' => $form])
$message->setFrom('from@domain.com')
	->setTo($form->email)
	->setSubject($form->subject)
	->send();
	//also you can use sendgrid substitutions
	->setSendGridSubstitution('template id', [
		':var1' => 'var1value',
		':var2' => 'var2value',
	]);
```