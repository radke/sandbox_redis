# Zabawy z redise 4 na zf3

Do php trzeba dodać rozszerzenie redis, gdzieś w php.ini lub innych plikach konfiguracyjnych, w zależności od tego co to za php, u mnie to plik /conf.d/redis.ini:

```extension=redis.so```

```
session.save_handler = redis
session.save_path = "tcp://127.0.0.1:6379?auth=haslo_do_redisa_ustawione_w_jego_configu"
```

Restart apache'a:


```$ sudo service apache2 restart```

Po ściągnięciu repozytorium trzeba odpalić composer'a:

```composer install```

Trzeba też ustawić sobie vhosta w apache'u:

W /etc/hosts wpisałem sobie ```127.0.0.1 redis```, aby się do tego odwoływać w ten właśnie sposób
a nie przez IP, jak kto lubi ;)

```
<VirtualHost *:80>
    DocumentRoot "/path/to/redis/public"
    <Directory "/path/to/redis/public">
        Options -Indexes +FollowSymLinks
        DirectoryIndex index.php
        Order allow,deny
        Allow from all
        AllowOverride All
        Require all granted
    </Directory>

    ServerName redis
</VirtualHost>
```
