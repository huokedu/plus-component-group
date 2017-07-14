# 圈子列表

```
GET /groups/joined
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
    "message": [
        "获取成功"
    ],
    "data": [
        {
            "id": 3,
            "title": "哈哈哈，我可以发帖了",
            "intro": "来来来来来，快看我嘚瑟不??",
            "is_audit": 0,
            "posts_count": 0,
            "members_count": 1,
            "created_at": "2017-07-14 09:39:14",
            "pivot": {
                "user_id": 2,
                "group_id": 3
            }
        },
        {
            "id": 2,
            "title": "group 2",
            "intro": "group2 description",
            "is_audit": 1,
            "posts_count": 4,
            "members_count": 1,
            "created_at": "2017-07-11 10:28:14",
            "pivot": {
                "user_id": 2,
                "group_id": 2
            }
        },
        {
            "id": 1,
            "title": "group 1",
            "intro": "group1 description",
            "is_audit": 1,
            "posts_count": 0,
            "members_count": 1,
            "created_at": "2017-07-11 10:28:14",
            "pivot": {
                "user_id": 2,
                "group_id": 1
            }
        }
    ]
}
```

> pivot 可以忽略, 中间关系
