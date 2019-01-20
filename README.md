# parameter-compatibility-api

For task description see [TASK.md](./TASK.md)

For proposed and chosen solution see [SOLUTIONS.md](./SOLUTIONS.md)

### Running this API from a virtual machine

If you have VirtualBox, vagrant and OS supporting NFS, you can run the Vagrant VM which will setup all the dependencies for you. Just clone this repo and in the root dir of the repository run

```$bash
vagrant up
```

Sit back and allow some time for the VM setup to run.

After the process completes, you should be able to see the API working at [http://localhost:8080/](http://localhost:8080/)

Any subsequenty mentioned commands should be run from the VMs project directory. To get there run

```bash
vagrant ssh
### It ssh connects to VM. Next commands are run in the VM shell
cd /var/www
```

### Running this API from your machine

You will have to take care of the dependencies yourself. This is what has to be available:

- PHP 7.2 with these extensions:
    - xml
    - json
    - PDO / PDO-mysql
    - ctype
    - iconv
    - gmp
    - mbstring
- MySQL / Maria DB server

Then follow these steps:

1. Clone this repository
2. Copy `.env.dist` file to `.env` and modify the database credentials to match your environment
3. Copy `phpspec.yml.dist` to `phpspec.yml` (modify it if you want/need to - defaults should work)
4. Copy `behat.yml.dist` to `behat.yml` (modify it if you want/need to - defaults should work)
5. Run `composer install` to setup the dependencies
6. Start the webserver: `php bin/console server:start 127.0.0.1:8080`

You should see the working API at [http://localhost:8080/](http://localhost:8080/)


### Data Fixtures

Initially the database is loaded with the minimal data set that was given in the example. 

However, there are a few more data fixtures available for basic testing. You can import them by running

```bash
php bin/console doctrine:fixtures:load -n --group=<group>
```  

Where `<group>` is one of:
- `example` - the one originally loaded
- `none` - not a real fixture - just a way to clear the DB empty
- `clothing` - a larger set of data with some arbitrary restrictions
- `roulette` - restrictions that would apply for choosing a square on the roulette table 

### Running the tests

The specs (a.k.e. unit tests) are written with PhpSpec and can be run with:

```bash
vendor/bin/phpspec run
```

The behavioural tests (end-to-end in this case) are done with Behat. Make sure the DB is clean before running them (use `none` data fixture). Run with:
```bash
vendor/bin/behat
```