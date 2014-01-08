phpMyDNS
========

## Installation

### PostgreSQL server

`apt-get install postgresql`

`apt-get install postgresql-server-dev-9.1`

`apt-get install php5-pgsql`

`apt-get install bind9`

### Add database user

`CREATE USER dnsuser WITH PASSWORD 'superPassword';`

`CREATE DATABASE dns;`

`GRANT ALL PRIVILEGES ON DATABASE dns to dnsuser;`

### Database structure

`psql -h localhost -Udnsuser -W dns < /var/www/phpMyDNS/extra/structure.sql`





Error codes
-----------

- `0` - OK
- `1`	- DB CONN
- `10` - NO AUTH
- `11` - IS AUTH member_add() member_auth()
- `12` - USERNAME ALREADY EXISTS
- `13` - USERNAME NOT SPECIFIED
- `14` - 
- `15` - 
- `16` - 
- `17` - 
- `18` - 
- `19` - 
- `20` - ZONE ALREADY EXISTS
- `21` - ZONE NOT EXISTS
- `50` - HOST NOT SPECIFIED
- `51` - ZONE_ID NOT SPECIFIED
- `52` - DESTINATION NOT SPECIFIED
- `53` - TYPE not specified
- `54` - Method unknown
- `55` - SQL: Member registration failed
- `56` - SQL: Member auth failed
