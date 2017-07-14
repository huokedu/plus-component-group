# 圈子动态列表

```
GET /groups/{group}/posts
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
            "id": 11,
            "title": "xxxxx",
            "content": "nsldfjslkjdfalkjdf",
            "group_id": 2,
            "views": 1,
            "diggs": 0,
            "collections": 0,
            "comments": 0,
            "user_id": 2,
            "is_audit": 1,
            "created_at": "2017-07-14 10:02:16",
            "updated_at": "2017-07-14 10:02:16"
        },
        {
            "id": 10,
            "title": "哈哈哈，我可以发帖了",
            "content": "来来来来来，快看我嘚瑟不??",
            "group_id": 2,
            "views": 3,
            "diggs": 1,
            "collections": 0,
            "comments": 3,
            "user_id": 2,
            "is_audit": 1,
            "created_at": "2017-07-13 07:39:16",
            "updated_at": "2017-07-14 09:55:37"
        }
    ]
}
```
