## 什么是 server session
    存储在 server 中的小块文件,因为 http 协议是无状态的，可以同 session 来保持状态。
## 如何产生 session
    手动开启 session。
    在 php 中对应 session_start()。
## 交互逻辑
- 1.一次请求过来，手动打开 session_start()。
- 2.server 发现该次请求没有 session id，那么就认为该次请求没有 session 数据，则会创建 session 数据，并返回 session id。
- 3.返回的 session id 一般会设置到浏览器的 Cookie 中，那么下次请求相同域名下地址，会在 Cookie 中带上 Session id，server 通过 session id 找到对应的 session 数据。
- 4.[如果浏览器禁止了 Cookie，那么可以将 session id 在 uri 中的 query 中带到 server](https://www.php.net/manual/zh/session.idpassing.php) 。

## 思考
    cookie 是 http 协议中的产物，而 session 不是。
    cookie 和 session 没有多大关系，除了可能会使用 cookie 来存储 session id 外。
    session 不一定必须要通过 cookie 来存储 session id，也可以通过其它方式，比如在 uri 的 query 中带上 session id。
    
    session 也可以理解为一种思想，就是在 server 保存数据，然后将数据的索引交给 client，client 每次请求的时候带上索引，就能通过索引拿到状态数据。
 
    