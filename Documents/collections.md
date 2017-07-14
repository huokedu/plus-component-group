# 圈子动态列表

```
GET /groups/posts/collections
```

### Parameters

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| limit | Integer | 可选，默认值 15 ，获取条数 |
| after | Integer | 可选，上次获取到数据最后一条 ID，用于获取该 ID 之后的数据。 |

> 列表为倒序

#### Response

```
Status: 200 OK
```
```json5
{
    "message": "获取成功",
    "data": [
        {
            "id": 3,
            "post_id": 11,
            "updated_at": "2017-07-14 10:25:15"
        },
        {
            "id": 1,
            "post_id": 10,
            "updated_at": "2017-07-14 07:23:23"
        }
    ]
}
```
