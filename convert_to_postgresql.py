#!/usr/bin/env python3
import re

def convert_mysql_to_postgresql(input_file, output_file):
    with open(input_file, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Supprimer les commandes MySQL spécifiques
    content = re.sub(r'/\*!40101.*?\*/', '', content, flags=re.DOTALL)
    content = re.sub(r'SET SQL_MODE.*?;', '', content)
    content = re.sub(r'SET time_zone.*?;', '', content)
    content = re.sub(r'START TRANSACTION;', 'BEGIN;', content)
    
    # Supprimer les backticks
    content = re.sub(r'`([^`]+)`', r'\1', content)
    
    # Convertir AUTO_INCREMENT
    content = re.sub(r'(\w+)\s+int\s+NOT\s+NULL\s+AUTO_INCREMENT', r'\1 SERIAL PRIMARY KEY', content, flags=re.IGNORECASE)
    content = re.sub(r'\bint\b', 'INTEGER', content, flags=re.IGNORECASE)
    
    # Convertir ENUM
    content = re.sub(r"role\s+enum\('([^']+)','([^']+)'\)[^,]*", r"role VARCHAR(20) CHECK (role IN ('\1', '\2')) DEFAULT 'etudiant'", content, flags=re.IGNORECASE)
    content = re.sub(r"status\s+enum\('([^']+)','([^']+)','([^']+)'\)[^,]*", r"status VARCHAR(20) CHECK (status IN ('\1', '\2', '\3')) DEFAULT 'pending'", content, flags=re.IGNORECASE)
    
    # Convertir TIMESTAMP
    content = re.sub(r'DEFAULT CURRENT_TIMESTAMP', 'DEFAULT NOW()', content, flags=re.IGNORECASE)
    
    # Supprimer ENGINE, CHARSET, COLLATE
    content = re.sub(r"ENGINE=\w+[^;]*", '', content, flags=re.IGNORECASE)
    content = re.sub(r"DEFAULT CHARSET=\w+[^;]*", '', content, flags=re.IGNORECASE)
    content = re.sub(r"COLLATE\s+\w+", '', content, flags=re.IGNORECASE)
    content = re.sub(r"AUTO_INCREMENT=\d+", '', content, flags=re.IGNORECASE)
    
    with open(output_file, 'w', encoding='utf-8') as f:
        f.write("-- PostgreSQL Schema pour Supabase\n\n")
        f.write(content)
    
    print(f"✅ Fichier créé: {output_file}")

if __name__ == "__main__":
    convert_mysql_to_postgresql('glossaire_db.sql', 'glossaire_db_postgresql.sql')