# RuleBook

PMMPプラグインです(Written in PHP)

## Description

初めて訪れたプレイヤーに任意で内容を設定できるルールブックを渡します(WrittenBook)

## Download

### [![PMMP Forum JP](https://forum.pmmp.ga/assets/logo-8gp5x8ya.png)](https://forum.pmmp.ga/d/45-rulebook)

## Demo

![demo1](https://user-images.githubusercontent.com/34952872/34479559-e3d3832e-efea-11e7-9ca9-a38586adca39.jpg)

![demo2](https://user-images.githubusercontent.com/34952872/34479616-2b5cfe8c-efeb-11e7-8b76-e58966ace60a.jpg)

## Usage

```yaml
# 本のタイトル
title: ルールブック
# 著者
author: オーナー
# 内容
# 一ページずつ書けます
#
# - type: text
#   data: 内容←ここだけ変えてください
#   
# ↑これで一ページです。増やしたければ同じ記述をもう一つ増やしてください
#
# 例:
page:
- type: text
  data: ルール
- type: text
  data: あけおめ
```

## Author

Twitter
[@xanadu2875](https://twitter.com/xanadu2875)

Lobi
[1a8ca](https://web.lobi.co/user/1a8ca6d4fdd1d87e0f26c68e18f08de6413f7d36)

## License

GPLLv3
