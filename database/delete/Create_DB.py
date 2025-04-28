import os

# 获取脚本路径（强制使用脚本所在目录）
base_path = os.path.dirname(os.path.abspath(__file__))

# 获取 table name
table_name = input("Enter the table name you want to create: ").strip()

# patent file 路径
patent_file = os.path.join(base_path, "database_ID_Patent.txt")

# 读取已存在的 patent
if os.path.exists(patent_file):
    with open(patent_file, "r") as f:
        existing_patents = f.read().splitlines()
else:
    existing_patents = []

# 不断要求输入，直到不重复为止
while True:
    code_patent = input("Enter the code patent  : ").strip().upper()
    if code_patent in existing_patents:
        print(f"✘ Patent '{code_patent}' already exists, please enter another.")
    else:
        with open(patent_file, "a") as f:
            f.write(code_patent + "\n")
        print(f"✔ Patent '{code_patent}' added.")
        break  # 不重复，跳出循环


# 生成 SQL 内容
sql_content = f"""CREATE TABLE {table_name} (
    {table_name}_id VARCHAR(20) PRIMARY KEY,
    auto_id INT AUTO_INCREMENT UNIQUE
);

DELIMITER //
CREATE TRIGGER before_{table_name}_insert 
BEFORE INSERT ON {table_name}
FOR EACH ROW
BEGIN
    IF NEW.{table_name}_id IS NULL THEN
        SET NEW.{table_name}_id = CONCAT('{code_patent}', LPAD(NEW.auto_id, 8, '0'));
    END IF;
END//
DELIMITER ;
"""

# 写入 SQL 文件
sql_file = os.path.join(base_path, f"{table_name}.sql")
with open(sql_file, "w") as f:
    f.write(sql_content)

print(f"\n✔ SQL script saved to '{sql_file}'")
