<?php

namespace Psr\Http\Message;

/**
 * 表示由客户端发往服务端的请求
 *
 * 根据 HTTP 规范，此接口包含以下属性：
 * - 协议版本
 * - HTTP 请求方法
 * - URI
 * - 请求头
 * - 请求体
 *
 * 在构造期间，如果没有提供主机头，
 * 实现必须尝试从提供的 URI 设置主机头
 *
 * 请求被认为是不可变的；必须实现所有可能更改状态的方法，
 * 使它们保留当前消息的内部状态，并返回包含更改状态的新实例。
 *
 * Interface RequestInterface
 * @package Psr\Http\Message
 */
interface RequestInterface extends MessageInterface
{
    /**
     * 检索消息的请求目标
     *
     * 检索消息的请求目标，按照它将出现的方式（客户端）、在请求时出现的方式（服务器）
     * 或者按照它为实例指定的方式（参见 withRequestTarget()）。
     *
     * 在大多数情况下，这将是组合 URI 的原始形式，除非向具体实现提供了一个值（参见
     * withRequestTarget())。
     *
     * 如果没有 URI，并且没有明确指定请求目标，则此方法必须返回 "/" 字符串
     *
     * @return string
     */
    public function getRequestTarget();

    /**
     * 返回包含指定请求目标的新实例
     *
     * 如果请求需要一个不是 origin-from 的请求目标，例如
     * - absolute-form
     * - authority-form
     * - asterisk-form
     * 该方法可用于创建具有指定请求目标的实例。
     *
     * 该方法的实现必须保证消息的不可变性，并且必须返回一个
     * 具有更改了请求目标的新实例。
     *
     * @see http://tools.ietf.org/html/rfc7230#section-5.3（请求消息中允许的各种请求目标）
     *
     * @param mixed $requestTarget
     * @return static
     */
    public function withRequestTarget($requestTarget);

    /**
     * 检索请求使用的 HTTP 方法
     *
     * @return string 返回当前请求使用的请求方法
     */
    public function getMethod();

    /**
     * 返回包含指定请求方法的新实例
     *
     * 虽然 HTTP 方法名通常是大写字符，但 HTTP 方法名是大小写敏感的，
     * 因此实现不应该修改给定的字符串。
     *
     * 此方法的实现必须保证消息的不可变性，并且返回一个包含新请求方法的
     * 新实例。
     *
     * @param string $method 请求方法（区分大小写）
     * @return static
     * @throws \InvalidArgumentException for invalid HTTP methods.
     */
    public function withMethod($method);

    /**
     * 检索 URI 实例
     *
     * 此方法必须返回 UriInterface 的实例。
     *
     * @see http://tools.ietf.org/html/rfc3986#section-4.3
     * @return UriInterface 返回一个表示请求 URI 的 UriInterface 实例
     */
    public function getUri();

    /**
     * 返回一个包含提供的 URI 的新实例
     *
     * 如果 URI 中包含主机信息，则必须返回的实例的主机信息。
     * 如果 URI 中不包含主机信息，则必须将任何之前存在的主机信息携带到要返回的新实例中。
     *
     * 通过将 '$preserveHost' 设置为 'true'，您可以选择保留主机头的原始状态。当 '$preserveHost'
     * 设置为 'true' 时，这个方法会以以下方式与主机交互：
     *
     * - 如果主机头丢失或者为空，并且新的 URI 中包含主机头信息，则此方法必须返回包含新的主机头信息的实例。
     * - 如果主机头丢失或者为空，并且新的 URI 中不包含主机信息，则返回的新实例中，不能更改主机头信息。
     * - 如果存在主机头信息且不为空，则此方法返回的新实例不能修改请求的主机头信息。
     *
     *
     * 此方法必须保证消息的不可变性，并且必须返回一个具有新的 UriInterface 的新实例。
     *
     * @see http://tools.ietf.org/html/rfc3986#section-4.3
     * @param UriInterface $uri 要使用的新的请求 URI
     * @param bool $preserveHost 是否保留 Host 标头的原始状态
     * @return static
     */
    public function withUri(UriInterface $uri, $preserveHost = false);
}