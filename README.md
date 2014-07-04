workflow-php
============

This is a sample in PHP demonstrating the complete Autodesk View and Data API workflow.

Originally created by galikaixin@iuxlabs.com, updated and modified by Jeremy Tammik, Autodesk.


Original documentation
----------------------

1. 程序基于view api完成，开发时使用v1版本
2. client为前端程序，server为后端程序，后端程序需要php5.4+支持，务必开启curl模块
3. 请修改server/config.php中的CLIENT_ID以及SECRET为您的信息
4. server/config.php中的DEFAULT_BUCKET参数为默认的bucketKey,可以用于整个业务逻辑的调试
5. 修改client/javascript/global.js下的opt.address为server端的http路径
6. 如有任何问题，可以通过galikaixin@iuxlabs.com和我联系，谢谢！

Google translated to English, this reads:

1. the program is based on the viewer api completed using the development version v1
2. client front-end program, server as a backend, the backend process requires php 5.4 + support, be sure to open the curl module
3. modify the server / config.php in CLIENT_ID for your information and SECRET
4. server / config.php in DEFAULT_BUCKET parameter is the default bucketKey, can be used to debug the whole business logic
5. opt. modify http address client / javascript / global.js under server-side path
6. if you have any questions, you can contact me via galikaixin@iuxlabs.com.

thank you!

History
-------

2014-06-24 PHP server up and running, initial integration of files from galikaixin@iuxlabs.com.
2014-07-02 bug fixes from galikaixin@iuxlabs.com.


Installation Notes for PHP on Mac
---------------------------------

I first worked on using grunt-php to run the PHP server:

https://www.npmjs.org/package/grunt-php

It is based on Grunt, the JavaScript task runner:

http://gruntjs.com

However, my npm installation broke, I had to reinstall it, and in the end the grunt appoach did not work as easily as expected.

I reverted to a simpler solution:

Set up the built-in Apache server as described in

https://discussions.apple.com/docs/DOC-3083

That worked.
