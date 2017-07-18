# plus-component-group

ThinkSNS+ 基础圈子应用

> 由于需要验证圈子和动态的审核情况，故所有接口中的{group},{post}都为必填项

#返回体字段说明
| 字段 | 描述 | 额外参数说明 |
|:----:|:----:| :----: |
| avatar | 圈子头像 | :----: |
| cover | 圈子背景图 | :----: |
| members | 圈子成员 | :----: |
| managers | 圈子管理员 | founder: 创始人| 
| commentslist | 动态列表中每条动态前5条评论 | :----: |
| reply_to_user_id | 在评论中被回复的用户ID | :----: |

# API文档

- [圈子列表](/Documents/groups.md)

- [创建圈子](/Documents/createGroup.md)

- [加入圈子](/Documents/joinGroup.md)

- [退出圈子](/Documents/leftGroup.md)

- [我加入的圈子](/Documents/joinedGroup.md)

- [圈子成员](/Documents/groupMembers.md)

- [圈子详情](/Documents/groupDetail.md)

- [圈子动态详情](/Documents/groupPostDetail.md)

- [创建圈子动态](/Documents/createGroupPost.md)

- [圈子动态列表](/Documents/groupPosts.md)

- [创建评论](/Documents/createGroupPostComment.md)

- [动态评论列表](/Documents/groupPostComments.md)

- [删除评论](/Documents/deleteGroupPostComment.md)

- [对动态点赞](/Documents/createGroupPostDigg.md)

- [动态点赞列表](/Documents/groupPostDiggs.md)

- [取消动态点赞](/Documents/deleteGroupPostDigg.md)

- [收藏动态](/Documents/collectionPost.md)

- [我收藏的动态](/Documents/collections.md)

- [取消收藏动态](/Documents/unCollections.md)



