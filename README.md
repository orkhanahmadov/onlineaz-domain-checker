[![Build Status](https://travis-ci.org/orkhanahmadov/onlineaz-domain-checker.svg?branch=master)](https://travis-ci.org/orkhanahmadov/onlineaz-domain-checker)
[![codecov](https://codecov.io/gh/orkhanahmadov/onlineaz-domain-checker/branch/master/graph/badge.svg)](https://codecov.io/gh/orkhanahmadov/onlineaz-domain-checker)
[![StyleCI](https://github.styleci.io/repos/175622936/shield?branch=master)](https://github.styleci.io/repos/175622936)
[![Maintainability](https://api.codeclimate.com/v1/badges/44909c35317fe57486a8/maintainability)](https://codeclimate.com/github/orkhanahmadov/onlineaz-domain-checker/maintainability)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/orkhanahmadov/onlineaz-domain-checker/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/orkhanahmadov/onlineaz-domain-checker/?branch=master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/orkhanahmadov/onlineaz-domain-checker/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)

## [online.az](https://online.az) Domain Checker

Simple dockerized cli application to periodically check for domain availability on [online.az](https://online.az)

### Installing

#### Pulling as Docker image (recommended)

- Run: ```docker run -dit --restart unless-stopped orkhanahmadov/onlineaz-domain-checker```

#### Building from source

- Clone this project
- Add your configuration to ```.env.example```
- Run ```docker build -t orkhanahmadov/onlineaz-domain-checker .``` 
- Run ```docker run -it orkhanahmadov/onlineaz-domain-checker```

To keep container running in background, even after restarting Docker daemon, simply run:

```docker run -dit --restart unless-stopped onlineaz-domain-checker```

##### Application requires initial setup and domain list to work! See Usage.

### Usage

To interact with application you need to get current running container ID. Run ``docker ps`` and look for container with ``orkhanahmadov/onlineaz-domain-checker`` image name and copy Container ID.

To setup application, run: 

```docker exec -it CONTAINER_ID_HERE onlineaz setup```

<br />

To add/update domains, run: 

```docker exec -it CONTAINER_ID_HERE onlineaz domains```

<br />

Application checks domain availability every hour. If you wish to check manually, run: 

```docker exec -it CONTAINER_ID_HERE onlineaz check```

### Contributing

PRs are welcome :)

### License
MIT
