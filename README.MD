# Putevki.ru web application

Steps to install app: 

### Backend
1. Upload files to directory upper than web root eg.:

Web-root: 
```bash
/var/www/www-root/data/www/online.putevki.ru/
```
App:
```bash
/var/www/www-root/data/production-app/
```
2. Run composer: 
```bash
composer install
```

3. Create dirs and set modes:
```bash
sudo mkdir cache
sudo mkdir cache/volt/
sudo chown user:group cache/
sudo chmod -Rf 777 cache/
sudo chmod -Rf 777 backend/views/
sudo chmod -Rf 777 frontend/views/

```

3. Edit config

### Frontend

1. Go to `assets-frontend`, then run
```bash
grunt
```
This command will create assets in needed path (config.ini).

2. Create symlink for folder `public` in web root

