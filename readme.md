# WeatherServerBot: A backend server to handle communication between a bot and the user.

This is a side project in Lumen, in order to understand a bit more about the Facebook Messenger API. This project handles communication
Between a user and a bot, they deliver some weather information to the user.

## Starting

The following instructions should be working to make a local implementation for testing.

### Previous requirements

There are some minimum requirements, all code is mounted in Lumen, and thanks to the file composer.json should be no problem to install some necessary things, the logic is done with pure PHP (not a third library),
So this section is just to give you the features I have and the least I think I should have to work well.

You are welcome!
PHP 5.0> (I use PHP 7 and I have no problem).
Composer
Enable cURL in your php.ini file.
You are welcome!
For the process of obtaining the information time that I use the [OpenWeatherMap] service (https://home.openweathermap.org/), you must [register an account] (https://home.openweathermap.org/users/sign_up) In the service and get the API KEY (there is a free plan).

It must have an access token from the page of your bot or service (the user enables a bot to communicate with a fanpage). There's an awesome quick-start tutorial in the [Message Documentation] section (https://developers.facebook.com/docs/messenger-platform/guides/quick-start) of Message Platform on Facebook.

### Installing

You are welcome!
1.- Clone this project on your PC, using git clone https://github.com/fernandosg/WeatherServerBot.git
2. Once the project is cloned, open your terminal and navigate to the project, once there, install the dependencies using: composer install.
3.- Open the folder of your project and edit the .env file, adding 2 environment variables:

WEATHER_API_KEY = THIS IS THE KEY TO THE OPENWEATHERMAP API
ACCESS_TOKEN = THIS IS THE ACCESS GAME THAT YOU MUST HAVE, THIS IS TO ENTER THE STEP TO REGISTER A FANPAGE.
You are welcome!

## Deployment

In the test process there is a need for Facebook to have communication with the backend application, so you need to open a way in which your PC and Facebook can exchange the messages, for this use [Ngrok] (https: // ngrok .com), this can expose localhost to the Internet.
There is need for 2 things:
1.- Run ngrok.
2.- Run a web server.

Once you download [Ngrok] (https://ngrok.com/), you must extract the .exe file into the project's public folder. Once you have the .exe file, open your terminal and position it in the public folder, once you have just run it in your terminal:

You are welcome!
Ngrok http PORT
You are welcome!

This should show the information about your secure tunnel, included the URL https and https, copy the https url, this url is the url for the webhook that is described in step 2 of the [quick start guide for Facebook] (https: / / Developers.facebook.com/docs/messenger-platform/guides/quick-start).

2.- Run a web server
Once the tunnel is done, you must run the web server that serve the project, I use the webserver that is available with PHP 7, this because I can define the port but, you could use this project,
For example with XAMPP (WAMPP), just make sure the port you have in XAMPP (WAMPP) is the one you put in Ngrok

Built with

* [Lumen] (https://lumen.laravel.com/) - The laravel microframework

## Authors

** Fernando Segura ** - [Twitter] (https://twitter.com/fernandosegom)

## License

This project is licensed under the MIT License
