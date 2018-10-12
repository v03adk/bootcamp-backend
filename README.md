viddyoze bootcamp backend

Random Quote API built using Symfony 3.4 framework, MySQL and API Platform 2.2

Implemented:
- CRUD for authors
- CRUD for quotes
- get random quote call
- apiKey in header authorization
- swagger documentation
- apiKey generation for sites (plugins)
- functional tests for endpoints

Not implemented:
- swagger documentation does not make test requests, needs more digging
- perfect UI on generating api key page

Notes:
- application is running on http://bootcamp-backend.unnam.de/
- only functional tests were done due to lack of time. more unit tests could be added to cover services: secutiry, data persister, data provider, doctrine, serializer
- as application built using standart functions of Symfony and API Platform it could be easily extended. for instance more authorization methods could be added like Oauth or JWT tokens.
