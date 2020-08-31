## 什么是 Cookie
    Cookie 是存储在浏览器中的一小块数据，该数据产生的规则是由 http 协议定义的。

## Cookie 特性
    浏览器在未禁止 Cookie 的情况下，每次请求某个地址，会通过 Cookie 属性，决定是否将对应地址(域名)下 Cookie 信息带到 http request 的 header 头中。
    
## 设置 Cookie
    服务器返回的 http response 的 header 中，通过 Set-Cookie header 头来设置 cookie。
    Cookie 中每一个键值对，都会对应一个 Set-Cookie。
    Set-Cookie:<cookie名>=<cookie值>
## 使用 Cookie
    浏览器发送 http 请求会将 Cookie 数据放在 request header 中
    Cookie: <cookie名>=<cookie值>; <cookie名>=<cookie值>
## Cookie 常用属性
    - 设置过期时间：
        - expire 过期时间:  Set-Cookie: <cookie名>=<cookie值>; expires=Mon, 31-Aug-2020 15:01:25 GMT
        - Max-Age 到期时间: Set-Cookie: <cookie名>=<cookie值>; Max-Age=3600
    - 安全设置：
        - secure 只能通过 https 设置/发送：          Set-Cookie: <cookie名>=<cookie值>; secure
        - HttpOnly JavaScript api 不能访问 Cookie: Set-Cookie: <cookie名>=<cookie值>; HttpOnly
    - 域名设置：Set-Cookie: <cookie名>=<cookie值>;domain=test.com;
        - 如果不设置那么只会在请求当前 url 域名时候才会发送 cookie。
        - 只能设置请求的 url 的域名的子域名，会在请求 url 的子域名共享该 cookie。
    - 路径设置：Set-Cookie: <cookie名>=<cookie值>;path=/;
        - path 默认为 / ，比如 www.baidu.com/
        - 如果 path 设置为 /test,那么请求 www.baidu.com ，不会带上 cookie，
          请求 www.baidu.com/tset 和 www.baidu.com/test/ 会带上 cookie。
## 常用场景
    - 保存鉴权信息。
    
## 拓展思想
    - 如果我们不是用浏览器发送 http 请求，但还是需要用到 Cookie 的话，可以通过构造 http request 中的 Cookie header 来传递 Cookie。
      当然这是一种思想，我们的 header 中需要传递的数据不一定要将他放到 Cookie header 中，我们可以自定 header，只要该 header 是和服务端有约定。
      
## 参考
- [HTTP cookies](https://developer.mozilla.org/zh-CN/docs/Web/HTTP/Cookies)
- [维基百科](https://zh.wikipedia.org/wiki/Cookie) 