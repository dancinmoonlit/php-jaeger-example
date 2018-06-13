# php-jaeger-example
jaeger with php example

# Requirements

- [Composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx)
- [Docker](https://docs.docker.com/engine/installation/)

## Running the example

This example has two services: frontend and backend. They both report 
trace data to jaeger.

To setup the example, run 

```bash
composer install
```

Once the dependencies are installed, run the services:

```bash
# Run jaeger:
composer run-jaeger

# Run frontend:
composer run-frontend

# Run backend
composer run-backend

```

Make the request to frontend:
 
```
curl http://localhost:9000
```


Check the trace in jaeger UI: [127.0.0.1:16686](http://127.0.0.1:16686)
