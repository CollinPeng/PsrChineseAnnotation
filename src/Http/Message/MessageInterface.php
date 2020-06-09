<?php

namespace Psr\Http\Message;

/**
 * HTTP 消息由客户端对服务器的请求和服务器对客户端的响应组成。
 * 这个接口定义了它们的公共方法。
 *
 * 消息是不可变得；必须实现所有可能更改状态的方法，
 * 使它们保留当前消息的内部状态，并返回包含更改状态的新实例。
 *
 * @see http://www.ietf.org/rfc/rfc7230.txt
 * @see http://www.ietf.org/rfc/rfc7231.txt
 *
 * @package Psr\Http\Message
 */
interface MessageInterface
{
    /**
     * 以字符串形式检索 HTTP 协议版本
     *
     * 返回的字符串必须仅仅返回 HTTP 协议的版本号（例如："1.1", "1.0"）.
     *
     * @return string HTTP protocol version.
     */
    public function getProtocolVersion();

    /**
     * 返回一个具有指定 HTTP 协议版本的新实例
     *
     * 版本字符串必须仅包含 HTTP 协议的版本号（例如："1.1", "1.0"）
     *
     * 该方法的实现必须保持消息的不可变性，
     * 并且返回具有新协议版本的实例。
     *
     * @param string $version HTTP 协议版本号
     * @return static
     */
    public function withProtocolVersion($version);

    /**
     * 检索所有的请求头的值
     *
     * 键表示通过网络发送的请求头的名称，
     * 每个值是与请求头关联的字符串数组。
     *
     *   // 将请求头转为字符串
     *   foreach ($message->getHeaders() $name => $value) {
     *     echo $name . ': ' . implode(', ', $values);
     *   }
     *
     *   // 迭代发送请求头
     *   foreach ($message->getHeaders() as $name => $values) {
     *     foreach ($values as $value) {
     *       header(sprintf('%s:  %s', $name, $value), false);
     *     }
     *   }
     *
     * 虽然 Header 名称不区分大小写，但是 getHeader()
     * 还是会保留最初指定 Header 时的准确大小写
     *
     * @return string[][] 返回消息头的关联数组
     *   每个键必须是 Header 名，每个值必须是该 Header 的字符串数组
     */
    public function getHeaders();

    /**
     * 检查指定的键是否存在于 Header 数组中（不区分大小写）
     *
     * @param string $name 不区分大小写的 Header 字段名称
     * @return bool 如果给定的 Header 名称存在则返回 true
     *     反之返回 false.
     */
    public function hasHeader($name);

    /**
     * 不区分大小写的通过给定 Header 名称来检索值。
     *
     * 此方法返回与给定名称对应的 Header 值数组。
     *
     * 如果给定的 name 不存在的话，此方法必须返回一个空数组
     *
     * @param string $name 不区分大小写的 Header 名称
     * @return string[] 指定名称的 Header 的字符串值数组。
     *   如果此名称不存在，则必须返回一个空数组
     */
    public function getHeader($name);

    /**
     * 检索给定名称的 Header 的逗号分隔的字符串值
     *
     * 此方法使用逗号分隔的字符串形式返回指定名称的 Header 的所有值。
     *
     * 如果此 Header 不存在与消息中，则此方法必须返回一个空字符串。
     *
     * @param string $name 不区分大消息的 Header 名称
     * @return string 用逗号分割的 Header 的所有值。如果此 Header 不存在
     *   于消息中，则必须返回一个空字符串。
     */
    public function getHeaderLine($name);

    /**
     * 返回一个新的实例，该实例使用指定的值替换指定标头中的值。
     *
     * 虽然标题的名称是不区分大小写的，但是这个函数会保留给定的大小写，
     * 并由 getHeaders() 中返回。
     *
     * 此方法的实现必须保持消息的不可变性，并返回一个新的实例来保存/添加的
     * 标头的值。
     *
     * @param string $name 不区分大小写的标头名称
     * @param string|string[] $value 标头的值(单个或者一组).
     * @return static
     * @throws \InvalidArgumentException for invalid header name or values.
     */
    public function withHeader($name, $value);

    /**
     * 为指定标头追加一个新的值，并返回一个新的实例
     *
     * 如过指定的标头存在，则为其追加一个新的值；
     * 如果不存在，则添加当前标头。
     *
     * 此方法的实现必须保持其不可变性，并且必须返回一个
     * 包含新实例来保持新的值。
     *
     * @param string $name 不区分大小写的标头名称
     * @param string|string[] $value 标头的值
     * @return static
     * @throws \InvalidArgumentException for invalid header names.
     * @throws \InvalidArgumentException for invalid header values.
     */
    public function withAddedHeader($name, $value);

    /**
     * 返回一个不包含指定标头的新实例
     *
     * 标头的解析必须不区分大小写
     *
     * 此方法的实现必须保持其不可变性，并且必须返回一个
     * 删除指定值的新实例。
     *
     * @param string $name 要删除的标头名称（不区分大小写）
     * @return static
     */
    public function withoutHeader($name);

    /**
     * 返回消息的主体。
     *
     * @return StreamInterface 将消息主体用流来返回
     */
    public function getBody();

    /**
     * 返回一个包含指定主体的新实例
     *
     * 主体必须是一个 StreamInterface 的对象。
     *
     * 此方法的实现必须保持其不可变性，并且返回一个
     * 包含指定实体的新实例。
     *
     * @param StreamInterface $body 主体
     * @return static
     * @throws \InvalidArgumentException When the body is not valid.
     */
    public function withBody(StreamInterface $body);

}