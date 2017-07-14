# 创建圈子

```
POST /groups
```

### Parameters

| 名称 | 类型 | 描述 |
|:----:|:----:|----|
| title | String | 必填, 圈子名称, max: 30 |
| avatar | Integer | 必填, 圈子图标 |
| intro | String | 必填, 圈子简介, max: 100 |
| cover | Integer | 必填, 圈子背景图 |


#### Response

```
Status: 201 OK
```
```json5
{
    "message": [
        "提交成功,请等待管理员审核"
    ],
    "id": 3
}
```
