# 系統名稱：系學會成員與活動管理系統  
**版本**：1.0  
**作者**：
- 輔仁大學資訊管理學系 二甲 陳庭毅 412401317 
- 輔仁大學資訊管理學系 二甲 吳宇燊 412261121 

**專案類型**：期末專案  

---

## 目錄  
1. [專案介紹](#專案介紹)  
2. [功能說明](#功能說明)  
    - [成員管理](#1-成員管理)  
    - [活動管理](#2-活動管理)  
    - [個別成員活動統計](#3-個別成員活動統計)  
3. [系統特色](#系統特色)  
4. [系統需求](#系統需求)  
5. [安裝與設置](#安裝與設置)  
6. [資料庫設計](#資料庫設計)  
7. [系統操作流程](#系統操作流程)  
8. [注意事項](#注意事項)  
9. [貢獻與聯繫方式](#貢獻與聯繫方式)  

---

## 專案介紹  

本系統是一個針對系學會設計的成員與活動管理系統，旨在提供高效且一致的管理工具，幫助系學會管理成員資料、追蹤活動參與情況及統計成員活躍度。系統提供高度整合的操作邏輯，所有資料之間相互關聯，可在刪除或修改操作時，自動同步更新相關記錄，確保資料的完整性與準確性。

---

## 功能說明  

### 1. 成員管理  

#### 功能概述  
管理系統中的所有成員資料，包括新增成員、查詢成員、編輯成員資料以及刪除成員。  

#### 特點：  
- **刪除同步處理**：若刪除某成員，系統將自動刪除該成員的活動紀錄與繳費資訊，無需手動操作，確保資料一致性。  

#### 使用方式  
1. **進入成員管理頁面**：  
   - 登入系統後，點擊導覽列的「成員管理」選項，跳轉至 `query.php` 頁面。

2. **查詢成員**：  
   - 使用搜尋欄輸入關鍵字，如姓名、學號、入學年份或職位，篩選符合條件的成員列表。

3. **新增成員**：  
   - 點擊「新增成員」按鈕，輸入基本資料，完成後自動更新成員列表。

4. **編輯成員**：  
   - 修改某成員的基本資料，系統會即時更新。

5. **刪除成員**：  
   - 刪除後，系統會同步刪除該成員在所有活動及繳費記錄中的資料，確保沒有遺漏。

---

### 2. 活動管理  

#### 功能概述  
- 提供活動的新增、查詢、編輯與刪除功能。  
- 系統設計支援成員與活動的動態關聯，任何操作都會自動反映在相關的統計數據中。  

---

### 3. 個別成員活動統計  

#### 功能概述  
- 查看成員參加的所有活動記錄，並統計其累積活動次數。  
- 系統動態更新統計數據，確保所有查詢結果即時且準確。

---

## 系統特色  

1. **高度整合性**：  
   - 系統中所有成員資料、活動紀錄及繳費資訊均相互關聯。  
   - 刪除某成員後，系統會同步刪除其所有相關記錄，無需額外操作，減少重複管理的麻煩。  

2. **一致性保證**：  
   - 利用資料庫的外鍵約束與邏輯檢查，確保數據一致性，避免孤立記錄或遺漏數據的情況。  

3. **動態更新數據**：  
   - 修改或新增記錄時，系統會即時更新所有統計數據，確保查詢結果準確無誤。

4. **彈性擴展性**：  
   - 系統可輕鬆新增角色類型或活動種類，滿足未來的擴展需求。

---

## 系統需求  

1. **伺服器需求**：  
   - PHP 7.4 或更新版本  
   - MySQL 5.7 或更新版本  
   - 支援外鍵約束的資料庫  

2. **瀏覽器需求**：  
   - 支援現代瀏覽器如 Google Chrome 或 Mozilla Firefox。  

---

## 資料庫設計  

### 1. `members` 表  

- **`id`**：自動遞增主鍵，唯一標識成員。  
- **`name`**：成員姓名。  
- **`student_id`**：成員學號，需唯一。  
- **`position`**：成員職位，如「會員」或「幹部」。  

### 2. `activity_logs` 表  

- **`id`**：活動記錄的唯一標識。  
- **`member_id`**：與成員表的外鍵關聯，確保記錄與成員匹配。  
- **`activity_name`**：活動名稱。  
- **`role`**：活動中的角色，如「會員」或「幹部」。  

---

## 系統操作流程  

1. **新增成員與活動記錄**：  
   - 管理員可透過簡單的操作新增成員或活動記錄，系統會即時更新數據庫。  

2. **刪除資料**：  
   - 刪除成員或活動後，系統會自動刪除與該記錄相關的所有資訊，確保資料庫整潔。  

3. **查詢與統計**：  
   - 系統提供精確且動態的查詢功能，支援複合條件篩選。

---

## 注意事項  

1. **刪除資料的影響**：  
   - 刪除成員會導致該成員的所有活動與繳費記錄同步刪除，請確認後操作。  

2. **數據一致性**：  
   - 若需直接修改資料庫，請確保遵守外鍵約束，避免數據不一致。  

3. **系統擴展性**：  
   - 系統支援靈活的擴展，包括新增角色類型或活動範疇，只需適當更新資料庫與前端邏輯即可。  

---

## 貢獻與聯繫方式
1. **貢獻**
   - 資管二甲 陳庭毅 412401317 ： 功能完備性、資料庫完整性、GitHub 架設
   - 資管二甲 吳宇燊 412261121 ： 網頁設計、後期維護、檢修
   - 資管二甲 齊　一 412401472 ： 提供技術指導(GitHub架設)

2. **聯繫方式**
   - 若有問題或建議，請聯繫： brianandowen@gmail.com

---
