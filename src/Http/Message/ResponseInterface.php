<?php

namespace Psr\Http\Message;

/**
 * 表示从服务端传出的响应。
 *
 * 根据 HTTP 协议的规范，此接口的实例包含下面这些属性：
 * - 协议版本
 * - 状态码和原因短语
 * - 响应头
 * - 响应的消息体
 *
 * 响应被认为是不可变得；所有有可能更改消息状态的方法必须
 * 保留消息当前的状态并返回一个包含更改状态后的新实例。
 *
 * @package Psr\Http\Message
 */
interface ResponseInterface extends MessageInterface
{
    /**
     * 获取响应状态码
     *
     * 状态码是服务器试图理解和满足请求的3位数的整数结果码
     *
     * @return int 状态码
     */
    public function getStatusCode();

    /**
     * 返回一个带有指定状态码和可选的原因短语的新实例。
     *
     * 如果没有指定原因短语，则此接口的实现可以从 RFC 7231 或者 IANA 中
     * 根据状态码选择推荐的原因短语。
     *
     * 此方法的实现必须保持消息的不变形，并且返回一个包含新状态和原因短语
     * 的实例。
     *
     * @see http://tools.ietf.org/html/rfc7231#section-6
     * @see http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
     * @param int $code 三位数的结果码
     * @param string $responPhrase 与状态码对应的原因短语，
     *     如果此参数没有提供，则从 HTTP 中规范中选择默认值。
     * @return static
     * @throws \InvalidArgumentException For invalid status code arguments.
     */
    public function withStatus($code, $responPhrase = '');

    /**
     * 获取与状态码相关联的原因短语。
     *
     * 因为在响应状态行中，原因短语不是必填元素，所以原因短语的值可以是空的。
     * 实现可以选择返回默认的 RFC 7231 推荐的原因短语（或者在 IANA HTTP 中列
     * 出的原因短语）。
     *
     * @see http://tools.ietf.org/html/rfc7231#section-6
     * @see http://www.iana.org/assignments/http-status-codes/http-status-codes.xhml
     * @return string 原因短语；如果没有则必须返回一个空字符串。
     */
    public function getReasonPhrase();
}