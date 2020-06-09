<?php

namespace Psr\Http\Message;

/**
 * 表示传入服务端的 HTTP 请求。
 *
 * 根据 HTTP 协议规范，此接口包含以下属性：
 *
 * - 协议版本
 * - 请求方法
 * - URI
 * - 请求头
 * - 请求体
 *
 * 另外它封装了服务端的信息，因为它已经到达应用程序的 CGI/PHP 环境：
 * - $_SERVER 中的值
 * - 提供的任何 cookie（$_COOKIE）
 * - 查询字符串参数（通常通过 $_GET 或者 parse_str 进行解析）
 * - 上传的文件（如果有的话）（通过 $_FILES）表示
 * - 反序列化后的请求体（$_POST）
 *
 * $_SERVER 的值必须表示为不可变得，因为它们表示了请求时应用程序的状态；
 * 因此，没有提供修改这些值的方法。其他值提供了这样的方法，因为它们可以从
 * $_SERVER 或请求体中恢复，并且可能需要在应用程序中进行处理。（例如：请求体
 * 参数可能会根据内容类型进行反序列化）。
 *
 * 另外，该接口还能识别通过内省请求派生和匹配其他参数的使用方法（例如，通过 URI
 * 路径匹配、解密 cookie 值、反序列化请求体、匹配用户的授权头等）。这些属性存储
 * 在 "attributes" 属性中。
 *
 * 请求被认为是不可变得；必须实现所有可能更改状态的方法，使它们保留当前消息的内部
 * 状态，并返回包含更改状态的新实例。
 *
 * @package Psr\Http\Message
 */
interface ServerRequestInterface extends RequestInterface
{
    /**
     * 检索服务器参数
     *
     * 检索与请求环境有关的数据，通常从 PHP 的 $_SERVER 超全局
     * 变量中获取。
     *
     * @return array
     */
    public function getServerParams();

    /**
     * 获取 Cookies
     *
     * 从请求中获取从客户端发来 cookie 数据。
     *
     * 数据必须与 $_COOKIE 超全局变量的结构兼容。
     *
     * @return array
     */
    public function getCookieParams();

    /**
     * 返回包含指定 cookie 信息的新实例。
     *
     * 数据不需要来自 $_COOKIE 超全局变量，但必须与
     * $_COOKIE 的结构兼容。通常，该数据将在实例化时
     * 注入。
     *
     * 此方法不能更新请求实例的相关 Cookie 头，
     * 也不能更新服务器参数中的相关值。
     *
     * 此方法的实现必须保证消息的不变性，并且返回一个
     * 包含指定数据的新实例。
     *
     * @param array $cookies 代表 cookie 的键/值对数组
     * @return static
     */
    public function withCookieParams(array $cookies);

    /**
     * 检索查询字符串参数。
     *
     * 检索反序列化后的查询字符串参数（如果有）。
     *
     * 注意：查询参数可能与 URI 或者服务器参数不同步。如果
     * 你需要获取原始值，您可能需要从 'getUri()->getQuery()' 或
     * 'QUERY_STRING' 服务器参数中解析查询字符串。
     *
     * @return array
     */
    public function getQueryParams();

    /**
     * 返回一个包含指定查询字符串参数的新实例。
     *
     * 这些值应该在传入请求的过程中保持不变。它们可以在实例化过程中
     * 被注入，比如从 $_GET 超全局变量中或者从其他值（比如 URI）中
     * 派生。在从 URI 解析参数的情况下，数据必须与 PHP 的 parse_str()
     * 返回的数据兼容，以便了解如何处理重复的查询参数以及如何处理嵌套集。
     *
     * 设置查询字符串的时候，不能更改请求的 URI，也不能更改服务器参数中的值。
     *
     * 此方法必须保持消息的不可变性，并且返回具有更新后的查询字符串的新实例。
     *
     * @param array $query 查询字符串数组，通常来自 $_GET。
     * @return mixed
     */
    public function withQueryParams(array $query);

    /**
     * 检索标准化文件上传数据。
     *
     * 此方法以规范化的树形式返回上传元数据，树的每个节点都是
     * Psr\Http\Message\UploadFileInterface 的实例。
     *
     * 这些值可以在实例化期间从 $_FILES 或者消息体中注入，也可以通过 willUploadedFiles()
     * 注入。
     *
     * @return array UploadedFileInterface 实例的数组树；如果没有数据，则返回空数组
     */
    public function getUploadedFiles();

    /**
     * 返回一个包含指定上传文件的新实例。
     *
     * 此方法必须保证消息的不可变性，并返回包含指定上传信息的新实例。
     *
     * @param array $uploadedFiles 包含 UploadedFileInterface 实例的数组树
     * @return static
     * @throws \InvalidArgumentException if an invalid structure is provided.
     */
    public function withUploadedFiles(array $uploadedFiles);

    /**
     * 获取请求体中提供的所有参数。
     *
     * 如果请求的 content-type 是 application/x-www-form-urlencoded
     * 或 multipart/form-data，并且请求方法是 POST，此方法必须返回 $_POST
     * 中的内容。
     *
     * 除此之外，此方法可能返回任何反序列化后请求体的结果；由于解析返回结构化数据，
     * 潜在类型必须为数组或者对象。如果缺少请求体则返回 null 值。
     *
     * @return null|array|object 序列化后的请求体，如果有的话，通常是数组或对象
     */
    public function getParsedBody();

    /**
     * 返回一个包含指定请求体参数的新实例。
     *
     * 它们可以在实例化期间注入。
     *
     * 如果请求的 content-type 是 application/x-www-form-urlencoded 或者
     * multipart/form-data，并且请求方法是 POST，那么使用此方法只设置 $_POST 的内容。
     *
     * 数据不一定来自 $_POST，但必须是反序列化请求体的结果。此方法参数只能接收数组或者对象，
     * 或者在没有可用的值时接受 null 值。
     *
     * 例如，如果使用 JSON 来作为请求数据的有效载核，则可以使用此方法创建具有反序列化参数的
     * 请求实例。
     *
     * 此方法的实现必须保证消息的不可变性，并返回具有已更新请求体的新实例。
     *
     * @param null|array|object $data 反序列化后的数据。它们通常是数组或者对象。
     * @return static
     * @throws \InvalidArgumentException 如果提供了不受支持的参数类型。
     */
    public function withParseBody($data);

    /**
     * 检索从请求中派生的属性
     *
     * 请求属性可以用来注入来自请求的任何参数：例如，路径匹配操作的结果、
     * 解密 cookie 的结果、非格式编码的消息体的反序列化结果等。属性是特
     * 定于应用程序和请求的，并且是可变的。
     *
     * @return mixed[] 从请求中派生的属性。
     */
    public function getAttributes();

    /**
     * 检索从请求中派生的单个属性
     *
     * 检索 getAttributes() 中描述的单个派生的请求属性。如果指定的属性不存在，
     * 则返回提供的默认值。
     *
     * 此方法消除了对 hasAttribute() 方法的需要，它允许设置一个用于在不存在指
     * 定属性时返回的默认值。
     *
     * @see getAttributes()
     * @param string $name 属性名称
     * @param mixed $default 当属性不存在时返回的默认值
     * @return mixed
     */
    public function getAttribute($name, $default = null);

    /**
     * 返回一个包含指定请求属性的新实例。
     *
     * 此方法允许设置 getAttributes() 中描述的单个派生自请求的属性。
     *
     * 此方法的实现必须保持消息的不可变性，并返回一个包含更新指定属性的
     * 新实例。
     *
     * @see getAttributes()
     * @param string $name 属性名称
     * @param mixed $value 属性值
     * @return static
     */
    public function withAttribute($name, $value);


    /**
     * 此方法返回一个删除了指定属性的新实例。
     *
     * 此方法允许删除 getAttributes() 中描述的单个从请求中派生的属性。
     *
     * 此方法的实现必须保证消息的不可变性，并返回删除了指定属性的新实例。
     *
     * @see getAttributes()
     * @param string $name 属性名称
     * @return static
     */
    public function withOutAttribute($name);
}