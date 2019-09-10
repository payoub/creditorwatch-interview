# CreditorWatch Interview

## The Task

> The CEO from CreditorWatch is very interested in SEO and how this can improve Sales. Every
> morning he logs into google.com.au and types in the same key words “creditorwatch” and
> counts down to see where and how many times the company, www.creditorwatch.com.au
> appears.
>
> Seeing the CEO do this every day, a smart software developer at CreditorWatch decides to
> write a small application for him that will automatically perform this operation and return the
> result to the screen. They design and code some software that receives a string of keywords
> (E.G. “creditorwatch”) and a URL (E.G. “www.creditorwatch.com.au”). This is then processed to
> return a list of numbers for where the resulting URL is found in the Google results.
>
> For example, “1, 10, 33” or “0”
>
> The CEO is only interested if their URL appears in the first 100 results.

## Running the project

This project uses docker and all configuration is stored in the docker directory.

To launch the project run the following from the root directory:

```
docker-compose up
```

The project will then be served at http://localhost:8080

## Testing the project

Tests are located in the tests directory and can be run using the following:

```
docker-compose exec php sh -c "cd /var/www/html/tests/ && php run.php"
```
