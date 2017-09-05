# 圈子列表

```
GET /groups
```

### Parameters

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| limit | Integer | 可选，默认值 15 ，获取条数 |
| after | Integer | 可选，上次获取到数据最后一条 ID，用于获取该 ID 之后的数据。 |
| search | string | 可选，搜索关键字 |

> 列表为倒序

#### Response

```
Status: 200 OK
```
```json5
[
    {
        "id": 1,
        "title": "group 1",
        "intro": "group1 description",
        "is_audit": 1,
        "posts_count": 0,
        "members_count": 1,
        "created_at": "2017-07-11 10:28:14",
        "is_member": 1,
        "avatar": {
            "raw": "1",
            "size": "1200x800",
            "file_id": 2
        },
        "cover": {
            "raw": "1",
            "size": "1200x775",
            "file_id": 1
        },
        "managers": [
            {
                "group_id": 1,
                "user_id": 2,
                "founder": 1
            }
        ]
    }
]
```
