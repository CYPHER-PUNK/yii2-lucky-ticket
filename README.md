Yii 2 Basic Application Ticket
================================

Требуется разработать:

    Некий веб сервис, который должен уметь обрабатывать определенный вами вид запроса (get или post) и выдавать ответ в формате json.
    Некий веб клиент, который умеет показывать пользователю форму ввода данных и отображать ответ (который рассчитывает сервис). Должен быть обеспечен ввод данных и динамическое отображение результатов расчета, т.е. пользователь печатает в поле ввода цифру - ему рядом, без перезагрузки страницы отображается результат.

Алгоритм следующий:

    Пользователь вводит количество разрядов в нумерации билета (на трамвай). Фоновая проверка проверяет, что это корректное число, не отрицательное, и не буквы, и что число разрядов четное. Под фоновой проверкой подразумевается, что результат проверки отображается сбоку сразу, по мере ввода данных.
    Рядом динамически отображается либо отрицательный результат проверки числа на валидность (см. предыдущий пункт), либо, если число корректно - количество комбинаций «счастливых» билетов в этом числе разрядов. Условие «счастливости» билета - сумма первой половины цифр номера равна сумме второй половины цифр номера (поэтому число разрядов должно быть четным), т.е. билет с номером 11, 2222 или 123312 является счастливым, а 44445444 - нет.
    Если расчет произведен корректно, то пользователь может нажать рядом кнопку и начать просматривать полный список счастливых комбинаций в билетах этой разрядности. Если комбинаций много (допустим, больше 100) то надо предусмотреть динамическую подгрузку следующей порции (infinity scroll).


DIRECTORY STRUCTURE
-------------------

      assets/             contains assets definition
      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      mail/               contains view files for e-mails
      models/             contains model classes
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources



REQUIREMENTS
------------

The minimum requirement by this application template that your Web server supports PHP 5.4.0.


INSTALLATION
------------
~~~
php composer.phar global require "fxp/composer-asset-plugin:1.0.0-beta4"
php composer.phar install
~~~

Now you should be able to access the application through the following URL, assuming `basic` is the directory
directly under the Web root.

~~~
http://localhost/basic/
~~~


CONFIGURATION
-------------

Also check and edit the other files in the `config/` directory to customize your application.
