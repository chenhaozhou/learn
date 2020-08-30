## 什么是sso
    sso 是 Single Sign On 的缩写，翻译过来就是「单点登录」。
    它是一种针对两个及两个以上的产品只需要登录其中一个产品，其它产品就不许要再次登录的解决方案。
    举个例子：如果公司有产品 a 和产品 b，登录其中任何一个产品，比如登录 a，那么其它产品，比如 b，就无须再次去登录鉴权就能获得对应权限。
## 实现方式
    针对不同的情况会有不同的实现方式，常见的就两种情况：
    1.如果多个产品具有相同的父域名，如：a.test.com、b.test.com;
    2.多个产品没有相同的父域名，如：test1.com、test2.com;
### 具有相同父域的多个产品
    核心思想：将已鉴权的信息保存在 cookie 中，设置 cookie 的 domain 属性允许子域能够共享该 cookie 从而实现 sso。
    实施逻辑：一般可以将登陆放到主域名所在的产品中，如果子域产品需要登陆，那么可以临时重定向到主域产品，登陆成功后再重定向到对于子域产品特定页面。
### 不同域名的产品
    针对不同域名的产品做单点登录相对于相同父域名这种情况要复杂一些。
    主要是不同域名不能利用浏览器的特性共享 cookie ，所以我们需要自己通过一定的「规则逻辑」来实现将鉴权信息同步到每个不同域的产品，这个「逻辑规则」目前业内基本认可使用 CAS.
    CAS 是 Central Authentication Service 的缩写，翻译过来就是「中心化鉴权服务」。
#### CAS
#### 术语
- Client： 用户
- Server： 用来负责单点登录的「中心服务」，既 Cas 服务
- Service： 需要使用单点登录的各个服务(应用产品)
#### CAS Ticket
    Ticket 是 Service、Client 和  Cas Server 登录交互的票据凭证，在整个交互逻辑中会存在三种类型的 Ticket。
- TGT(Ticket Grangting Ticket)：判断用户是否在「Cas 服务」有过登录的 Ticket。该 Ticket 存储在「Cas 服务」中的 Session 中，如果存在 Ticket ，则表明用户有在「Cas 服务」中登录。
- TFC(Ticket Grangting Cookie)：「Cas 服务」会生成 TGT 存储在 Session 中，而 TFT 就是该 Session 的 Session id 保存在浏览器的 Cookie 中。
- TC(Service Ticket)：「Cas 服务」为 Client（用户）的某一 Service（产品）颁发的登录票据，Server 可以通过 TC 去 「Cas 服务」中判断是否已鉴权并可获取鉴权信息。
#### CAS Ticket 之间的关系
    TGT 存储在 「Cas 服务」的 Session 中，TFC 为该 Session 的Session id。
    TFC 被存储在 「Cas 服务」域下面的 Cookie 中，每次访问 「Cas 服务」会将 TFC 带到 「Cas 服务」，然后在 Session 中查看是否存在对应的 TFC,可以判断该浏览器是否登录过 「Cas 服务」。
    TC 「Cas 服务」通过校验 Session 中 TGT 是否有效，有效才会颁发一个 TC 给对应的 Service。
#### CAS api
    CAS 的 Server 中心服务一般需要四个 api 。
- login： 登录 api，用于 Client（用户）鉴权登录。
- logout：登出 api，用户 Client（用户）登出。
- validate：「Cas 服务」通过判断自己 Cookie 中是否存在 TFC 并校验 TFC 是否有效来确定用户是否已经登录过 「Cas 服务」。
- serviceValidate：接受 Service 请求的 TS 凭证来验证是否已登录鉴权，并获取登录鉴权的信息。
#### 详细逻辑交互流程
    假定我们现在有三台服务，分别是：Cas 服务、A（c.com） 服务 和 B（b.com）服务。
    
- 请求 A 服务需要鉴权的接口，如果该请求没有鉴权信息，A 服务会重定向到 「Cas 服务」的 /validate 接口。
- 「Cas 服务」会通过确定该次请求 Cookie 中是否存在 TFC 并认为其有效来确定用户是否已经登录「Cas 服务」，如果没有，则重定向到 「Cas 服务」的登录界面。
- 用户在 「Cas 服务」的登录界面成功登录后，「Cas 服务」会生成 TFT、TFC 和 TC ,然后重定向到 A 服务，并且将 TFC 设置到 「Cas 服务」的 Cookie，TC 交给 A 服务。
- A 服务拿到 TC 后请求 「Cas 服务」的 /serviceValidate 接口，去校验 TC 是否有效，并获取用户登录鉴权信息，并将鉴权成功的信息保存到 A 服务，至此 A 登录成功。
- 请求 B 服务需要鉴权的接口，如果该请求没有鉴权信息，B 服务会重定向到 「Cas 服务」的 /validate 接口。
- 「Cas 服务」该次请求的 Cookie 中存在 TFC 并校验成功，说明用户已经成功登录，这个时候就就直接返回给 B 服务 TC。
- B 服务拿到 TC 后请求 「Cas 服务」的 /serviceValidate 接口，校验 TC 是否有效，并获取用户登录鉴权信息，并将鉴权成功的信息保存到 B 服务，至此 B 服务成功登录。
- 到此 A、B 服务均已成功登录，后面在需要请求需要鉴权的 api ，在没有退出登录的情况下，也不需要在去请求 「Cas 服务」，因为 A、B 鉴权信息都存在各自的服务(Cookie、Session)中。
## 参考：
- [前端需要了解的 SSO 与 CAS 知识](https://juejin.im/post/6844903509272297480#heading-12)
- [基于 CAS 实现通用的单点登录解决方案（一）：CAS 原理及服务端搭建](https://xueyuanjun.com/post/9774)