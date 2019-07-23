### Redis有哪些数据结构
字符串String、字典Hash、列表List、集合Set、有序集合SortedSet。  
高级数据结构有HyperLogLog、Geo、Pub/Sub。

### 使用过Redis分布式锁么，它是什么回事？
先拿setnx来争抢锁，抢到之后，再用expire给锁加一个过期时间防止锁忘记了释放。
- 如果在setnx之后执行expire之前进程意外crash或者要重启维护了，那会怎么样？