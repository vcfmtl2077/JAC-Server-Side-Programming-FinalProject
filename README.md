## Building and running your application with docker

Under the root of project directory.

When you're ready, start your application by running:
```
docker compose up --build
```

OR

When you need to apply your changes by running:
```
docker compose watch
```
Any changes to the application's source files on your local machine will now be immediately reflected in the running container.

Stop and remove the running containers:
```
docker compose down
```

Your application will be available at http://localhost:9000/hello.php .

PHP Admin will be available at http://localhost:8080/index.php . Username: `root`. Password: `example`.

## Prject folder structure
```
project
├── Dockerfile         ........................    docker config file
├── README.md          ........................    readme
├── compose.yaml
├── composer.json      ........................    docker compose config file
├── composer.lock
├── db                 ........................    docker database secret folder
│   └── password.txt
├── src                ........................    project source code folder
│   └── db
│       └── config.php
└── tests              ........................    project test code folder
    └── HelloWorldTest.php
```