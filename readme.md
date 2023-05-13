<p align="center"><img src="https://hypersoftwares.com/hyperlogo.png"></p>

# HYPERBOOKING


## How To Setup

Follow The Steps:-

1. Clone this Git Repo
2. Move to your project folder 
3. composer update
4. npm install
5. npm run dev
6. copy .env.example to .env
7. php artisan key:generate
8. php artisan migrate
9. php artisan db:seed (run for dummy data)
10. php artisan websocket:serve (Only for realtime system / response)

## .env Config
(These configuration for realtime system / response )

APP_URL=http://localhost/name-of-project-root-folder/ 

BROADCAST_DRIVER=pusher

PUSHER_APP_ID=myID

PUSHER_APP_KEY=myKey

PUSHER_APP_SECRET=mySecret

PUSHER_APP_CLUSTER=mt1


## Login password for dummmy data

### For Admin  
* user: admin@gmail.com
* password: admin

### For Driver  
* user: driver email
* password: user123

### For Suppliers  
* user: supplier email
* password: user123

### For Staff  
* user: staff email
* password: staff123
