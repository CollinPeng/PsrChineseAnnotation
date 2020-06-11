<?php

namespace Psr\Http\Message;

/**
 * 描述了一个数据流
 *
 * 通常来说，一个实例会包装一个 PHP 流；这个接口提供了
 * 一个围绕最常见操作的包装器，包括将整个流序列化为一个
 * 字符串。
 *
 * @package Psr\Http\Message
 */
interface StreamInterface
{
    /**
     * 将流中转换为字符串。
     *
     * 此方法必须在读取数据之前尝试寻找流的开始，
     * 并读取流，直到抵达终点。
     *
     * 警告：这可能会尝试将大量数据加载到内存中。
     *
     * 为了符合 PHP 的字符串转换操作，这个方法必须不引起异常。
     *
     * @see http://php.net/manual/en/language.oop5.magic.php#object.tostring
     * @return string
     */
    public function __toString();

    /**
     * 关闭流并释放资源。
     *
     * @return void
     */
    public function close();

    /**
     * 释放流资源
     *
     * 流资源被释放后，流处于不可用状态
     *
     * @return resource|null 底层资源（如果有的话）
     */
    public function detach();

    /**
     * 获取流大小（如果已知的话）
     *
     * @return int|null 返回大小（以字节为单位），如果获取不到则返回 null
     */
    public function getSize();

    /**
     * 返回当前读写指针。
     *
     * @return int 在文件中的指针位置
     * @throws \RuntimeException or error.
     */
    public function tell();

    /**
     * 判断是否在流的末尾。
     *
     * @return bool
     */
    public function eof();

    /**
     * 判断流是否可搜索.
     *
     * @return bool
     */
    public function isSeekable();

    /**
     * 在流中寻找某个位置
     *
     * @see http://www.php.net/manual/en/function.fseek.php
     * @param int $offset 流的偏移量
     * @param int $whence 指定基于偏移量计算位置的方法。有效值与 PHP
     *     内置的 'fseek()' 的 $whence 值相同。
     *     SEEK_SET: 设置位置等于偏移量的字节数
     *     SEEK_CUR: 设置位置为当前位置加上偏移量
     *     SEEK_END: 设置位置为流的末端加上偏移量。
     * @throws \RuntimeException on failure.
     */
    public function seek($offset, $whence = SEEK_SET);

    /**
     * 寻找流的开始
     *
     * 如果流是不可搜寻的，则此方法应该抛出异常。
     * 否则将执行 seek(0).
     *
     * @see seek()
     * @see http://www.php.net/manual/en/function.fseek.php
     * @throws \RuntimeException on failure.
     */
    public function rewind();

    /**
     * 判断流是否可写。
     *
     * @return bool
     */
    public function isWritable();

    /**
     * 将数据写入流中.
     *
     * @param string $string 要写入到流中的字符串.
     * @return int 返回写入到流中字节数
     * @throws \RuntimeException on failure.
     */
    public function write($string);

    /**
     * 判断流是否可读.
     *
     * @return bool
     */
    public function isReadable();

    /**
     * 从流中读取数据。
     *
     * @param int $length 从流中读取最多 $length 字节的数据。
     *     如果底层流调用返回的数据较少，则返回的字节数可能会少于 $length.
     * @return string 返回从流中读取到的数据，如果流中没有可用字节，
     *     则返回空字符串
     * @throws \RuntimeException if an error occurs.
     */
    public function read($length);

    /**
     * 返回剩余的内容。
     *
     * @return string
     * @throws \RuntimeException if unable to read.
     * @throws \RuntimeException if error occurs while reading.
     */
    public function getContents();

    /**
     * 以关联数组的形式获取流的元数据，或检索一个特定的键。
     *
     * 反会的键值与 PHP 的 stream_get_meta_data() 函数返回的键
     * 值相同。
     *
     * @see http://php.net/manual/en/function.stream-get-meta-data.php
     * @param string $key 要检索的特定元素
     * @return array|mixed|null 如果没有提供 $key，则返回一个关联数组。
     *     如果提供了 $key，并找到对应的值，则返回一个特定的键值，如果没有
     *     找到键值，则返回 null.
     */
    public function getMetaData($key = null);

}