# SRSBSNS

## Setup

### Get the project running

```
git clone https://github.com/rqpt/srsbsns
cd srsbsns
composer install

# We're using sqlite, so doctrine:database:create isn't needed.
php bin/console doctrine:schema:create

symfony server:start --open
```


### Testing API endpoints

**Use the API_KEY in .env, or set your own.**

#### Create Contact

```sh
curl -i -X POST http://127.0.0.1:8000/api/contacts \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H 'Authorization: Bearer "$API_KEY"' \
  -d '{
    "name": "PE",
    "surname": "Vermeulen",
    "phone_number": "0674417057",
    "email": "ernstvermeulen@proton.me"
  }'
```

#### Delete Contact

```sh
 curl -i -X DELETE http://127.0.0.1:8000/api/contacts/33 \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H 'Authorization: Bearer "$API_KEY"'
```

## What I tested, and why.

I wrote an application test to test if the contact create form successfully
submits, redirects the user, and persists the contact.

The reason why I wrote it over some unit tests or something, is because
there isn't any logic here complicated enough to warrant a unit test.

I wanted to see if the main goals of the application is achieved or not.

## What I would have done differently or added with more time

I could have attempted to containerise the application, added contact update
endpoints, paginated the contact index.

I don't think everything being on one page looks too bad, but maybe splitting
the contact create form into it's own page would have been nicer.

A couple of changes I made were straight on to master, and I'm trying to get
into the habit of making PRs for every branch I finish, and to review them
before merging to main. I'm getting there, but I slip up from time to time due
to impatience.
