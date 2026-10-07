# Hospital de Anomalías — CRUD completo (PHP + PostgreSQL, con docker-compose)

Misma aplicación que la versión de las 2 redes (Create, Read x2, Update,
Delete), pero levantada con **docker-compose** y con un **volumen nombrado**
para que los datos de PostgreSQL sobrevivan aunque borres los contenedores.

Esta versión usa el **puerto 8082** (no el 8081) a propósito, para poder
tener ambas versiones del proyecto corriendo al mismo tiempo sin que se
peleen por el puerto. Tampoco fija nombres de contenedor (`hosp-web`,
`hosp-db`) a propósito — Docker Compose les pone su propio nombre único
(con el prefijo del proyecto), así que no chocan con los contenedores de la
versión manual aunque estén corriendo los cuatro a la vez.

## Qué cambia respecto a la versión sin compose

- Ya no hay que crear redes a mano (`docker network create`) ni conectar
  los contenedores con `docker network connect` — el `docker-compose.yml`
  ya declara las 2 redes (`red-web` y `red-db`) y a qué red pertenece cada
  servicio, así que Compose las crea y conecta todo solo al levantar.
- Ya no hay que cargar el `schema.sql` a mano con `docker cp` + `psql -f` —
  Postgres lo ejecuta solo la primera vez que arranca con el volumen vacío
  (ver `docker-compose.yml`).
- Los datos ahora viven en un **volumen nombrado** (`db_data`), no dentro
  del contenedor. Eso significa: si borras el contenedor `hosp-db` y lo
  vuelves a crear, tus pacientes registrados **siguen ahí** — algo que no
  pasaba antes (borrar el contenedor de Postgres sin volumen borraba todo).

## Un solo archivo describe todo: `docker-compose.yml`

```yaml
services:
  hosp-web:      # Cont1: PHP + Apache (se construye con el Dockerfile de esta carpeta)
    build: .
    ports:
      - "8082:80"
    depends_on:
      - hosp-db
    networks:
      - red-web  # hosp-web pertenece a las DOS redes -> por eso ve a hosp-db
      - red-db

  hosp-db:       # Cont2: PostgreSQL
    image: postgres:16
    environment:
      POSTGRES_PASSWORD: hospital123
      POSTGRES_DB: hospital_anomalias
    volumes:
      - db_data:/var/lib/postgresql/data              # volumen nombrado (persistencia)
      - ./schema.sql:/docker-entrypoint-initdb.d/schema.sql  # se ejecuta solo
    networks:
      - red-db   # hosp-db SOLO pertenece a red-db -> nunca ve nada de red-web

volumes:
  db_data:       # aquí se declara el volumen nombrado

networks:
  red-web:       # aquí se declaran las 2 redes
  red-db:
```

Es exactamente la misma idea que en la versión manual (`docker network
connect`): `hosp-web` queda en las dos redes y hace de puente, `hosp-db`
solo está en la suya. La diferencia es que aquí no lo escribes con un
comando aparte — lo describes de una vez dentro del mismo archivo, en la
lista `networks:` de cada servicio.

## Cómo levantarlo (parado dentro de esta carpeta)

```bash
docker compose up -d --build
```

Ese único comando: construye la imagen de `hosp-web`, descarga la de
Postgres si no la tienes, crea las 2 redes (`red-web` y `red-db`), crea el
volumen `db_data`, y levanta ambos contenedores ya conectados entre sí,
cada uno en la red que le corresponde.

Espera unos 10-15 segundos (Postgres tarda en inicializar la primera vez)
y abre:

```
http://localhost:8082
```

Deberías ver ya los 2 pacientes de ejemplo en "Reporte general" — sin haber
tenido que cargar el `schema.sql` a mano.

## Comandos útiles

```bash
docker compose ps              # ver el estado de los 2 servicios
docker compose logs hosp-db    # ver los logs de la base de datos
docker compose down            # apaga y borra los CONTENEDORES (el volumen se queda)
docker compose down -v         # apaga y borra TAMBIÉN el volumen (borra los datos)
docker volume ls               # ver el volumen nombrado creado (algo como "hospital-crud-php-compose_db_data")
docker volume inspect <nombre-del-volumen>   # ver dónde vive físicamente en tu máquina
```

## Evidencia de persistencia para tu documento

Una demostración clara de que el volumen nombrado funciona:

```bash
# 1. Con la app corriendo, registra un paciente desde el navegador.
# 2. Borra el contenedor de la base de datos (sin tocar el volumen):
docker compose stop hosp-db
docker compose rm -f hosp-db
# 3. Vuelve a levantarlo:
docker compose up -d hosp-db
# 4. Entra de nuevo a http://localhost:8082 → Reporte general
#    El paciente que registraste sigue ahí, aunque el contenedor
#    de la base de datos se volvió a crear desde cero.
```

## Evidencia de que ambos servicios se ven entre sí

```bash
docker compose exec hosp-web getent hosts hosp-db
```

Si regresa una IP, se ven — y aquí ni siquiera tuviste que conectarlos
manualmente, ya quedó declarado en el `docker-compose.yml`.

## Evidencia de las 2 redes por separado (para tu documento)

```bash
docker network ls | grep hospital-crud-php-compose
docker network inspect hospital-crud-php-compose_red-web
docker network inspect hospital-crud-php-compose_red-db
```

En `..._red-web` solo debe aparecer `hosp-web`. En `..._red-db` deben
aparecer **los dos** — la misma prueba que en la versión manual, solo que
estas redes las creó Compose por ti, con el nombre del proyecto como
prefijo (para no chocar con las `red-web`/`red-db` sueltas de la versión
sin compose).
