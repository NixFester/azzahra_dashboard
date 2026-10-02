import re
import os

def strip_sql(file_path):
    print(f"Processing {file_path}...")
    with open(file_path, 'r', encoding='utf-8', errors='ignore') as f:
        content = f.read()

    lines = content.splitlines()
    output = []
    
    i = 0
    n = len(lines)
    
    while i < n:
        line = lines[i]
        
        # 1. Add DROP TABLE IF EXISTS before CREATE TABLE
        if line.startswith("CREATE TABLE "):
            match = re.search(r'CREATE TABLE `([^`]+)`', line)
            if match:
                tbl_name = match.group(1)
                # Check if previous non-empty line isn't already DROP TABLE
                prev_line = output[-1] if output else ""
                if not (tbl_name in prev_line and "DROP TABLE" in prev_line):
                    output.append(f"DROP TABLE IF EXISTS `{tbl_name}`;")
            output.append(line)
            i += 1
            continue

        # 2. Handle INSERT INTO block -> keep maximum 1 sample tuple row
        if line.startswith("INSERT INTO "):
            stmt_lines = [line]
            while i + 1 < n and not stmt_lines[-1].rstrip().endswith(';'):
                i += 1
                stmt_lines.append(lines[i])
            
            full_stmt = "\n".join(stmt_lines)
            
            # Match header
            match = re.search(r'^(INSERT INTO `[^`]+` (?:\([^)]+\)\s*)?VALUES\s*)', full_stmt, re.DOTALL | re.IGNORECASE)
            if match:
                header = match.group(1)
                remainder = full_stmt[match.end():]
                
                # Extract first tuple (...)
                tuples = []
                in_string = False
                str_char = None
                escape = False
                paren_depth = 0
                current_tuple = []
                
                for char in remainder:
                    if escape:
                        current_tuple.append(char)
                        escape = False
                        continue
                    
                    if char == '\\':
                        current_tuple.append(char)
                        escape = True
                        continue
                    
                    if in_string:
                        current_tuple.append(char)
                        if char == str_char:
                            in_string = False
                            str_char = None
                        continue
                    else:
                        if char in ("'", '"'):
                            in_string = True
                            str_char = char
                            current_tuple.append(char)
                            continue
                        
                        if char == '(':
                            if paren_depth == 0:
                                current_tuple = ['(']
                            else:
                                current_tuple.append('(')
                            paren_depth += 1
                        elif char == ')':
                            paren_depth -= 1
                            current_tuple.append(')')
                            if paren_depth == 0:
                                tuples.append("".join(current_tuple))
                                current_tuple = []
                                break
                        elif paren_depth > 0:
                            current_tuple.append(char)
                
                if tuples:
                    new_stmt = header + "\n" + tuples[0] + ";"
                    output.append(new_stmt)
                else:
                    output.append(full_stmt)
            else:
                output.append(full_stmt)

            i += 1
            continue

        # 3. Reset AUTO_INCREMENT in ALTER TABLE statements
        if "AUTO_INCREMENT=" in line:
            line = re.sub(r'AUTO_INCREMENT=\d+', 'AUTO_INCREMENT=1', line)
            output.append(line)
            i += 1
            continue

        output.append(line)
        i += 1

    result = "\n".join(output)
    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(result)

    print(f"Successfully stripped {file_path}. New size: {len(result)} bytes ({len(output)} lines).")

if __name__ == '__main__':
    strip_sql('azzahra2_absensi.sql')
    strip_sql('azzahra2_azza.sql')
