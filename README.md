# Record Replay Examples 

## Challenge

Using in-memory fakes to replace secondary adapters is very powerful, it allows to have tests that run under a second (As I presented in this article : [Test All your Business Logic in less than 1 second ](https://dev.to/etienneleba/test-all-your-business-logic-in-less-than-1-second-2n84)). 
It also comes with some tradeoffs : 
- In-memory fakes can be hard to maintain
- In-memory fakes and real implementations behaviors can diverge 

## Record / Replay 

Mostly use in integration tests (Ex : [php-vcr](https://github.com/php-vcr/php-vcr)), this technique can be also use to record and replay to fake the behavior of the secondary adapters (Repository, gateway, etc). 

I created a helper class [RecordReplay](./src/RecordReplay/RecordReplay.php) able to create proxy around a secondary adapter. Since the proxy implements the same interface it will be able to record every call to the secondary adapter.


## Examples 

### Classic Symfony Structure 

[./tests/PostTest](./tests/PostTest.php)

### Hexagonal Structure

[./tests/BookARoomTest](./tests/BookARoomTest.php)
