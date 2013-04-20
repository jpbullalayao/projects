# Author:       Jourdan Bul-lalayao
# Module:       codegen.py
# Purpose:      Generate a python executable file through bytecode for Mini-Triangle
#               grammar text files.
# Note:         Requires user to input text file into command line while running codegen.py

from byteplay import *
from types import CodeType, FunctionType
import pprint

import scanner
import parser2 as parser
import ast
import sys

import marshal
import struct
import time


class CodeGenError(Exception):
    """ Code Generator Error """

    def __init__(self, ast):
        self.ast = ast

    def __str__(self):
        return 'Error at ast node: %s' % (str(self.ast))


class CodeGen(object):

    def __init__(self, tree):
        self.tree = tree
        self.code = []
        self.env = {}

    def generate(self):

        if type(self.tree) is not ast.Program:
            raise CodeGenError(self.tree)
        
        self.gen_command(self.tree.command)
        self.code.append((RETURN_VALUE, None))

        pprint.pprint(self.code)
        
        code_obj = Code(self.code, [], [], False, False, False, 'gencode', '', 0, '')
        code = code_obj.to_code()
        func = FunctionType(code, globals(), 'gencode')
        return func       
        
    def gen_command(self, tree):
        
        if type(tree) is ast.LetCommand:
            self.gen_decl(tree.declaration)
            self.gen_command(tree.command)
            
        elif type(tree) is ast.WhileCommand:
            l1 = Label()
            l2 = Label()
            self.code.append((l1, None))
            self.gen_expr(tree.expression)
            self.code.append((POP_JUMP_IF_FALSE, l2))
            self.gen_command(tree.command)
            self.code.append((JUMP_ABSOLUTE, l1))
            self.code.append((l2, None))
        
        elif type(tree) is ast.IfCommand:
            l1 = Label()
            l2 = Label()
            self.gen_expr(tree.expression)
            self.code.append((POP_JUMP_IF_FALSE, l1))
            self.gen_command(tree.command1)
            self.code.append((JUMP_ABSOLUTE, l2))
            self.code.append((l1, None))
            self.gen_command(tree.command2)
            self.code.append((l2, None))
        
        elif type(tree) is ast.SequentialCommand:
            self.gen_command(tree.command1)
            self.gen_command(tree.command2)
        
        elif type(tree) is ast.AssignCommand:
            self.gen_expr(tree.expression)
            self.code.append((STORE_FAST, tree.variable.identifier))
        
        elif type(tree) is ast.CallCommand:
            identifier = tree.identifier
            if identifier == 'putint':
                if type(tree.expression) is ast.VnameExpression:
                    self.code.append((LOAD_FAST, tree.expression.variable.identifier))
                elif type(tree.expression) is ast.IntegerExpression:
                    self.code.append((LOAD_CONST, tree.expression.value))
                else:
                    raise CodeGenError(tree.expression)
                self.code.append((PRINT_ITEM, None))
                self.code.append((PRINT_NEWLINE, None))
                self.code.append((LOAD_CONST, 0)) 

            elif identifier == 'getint':
                self.code.append((LOAD_GLOBAL, "input"))
                self.code.append((CALL_FUNCTION, 0))
                self.code.append((STORE_FAST, tree.expression.variable.identifier))
            else:
                raise CodeGenError(tree)
        else:
            raise CodeGenError(tree)
                
        
    def gen_expr(self, tree):
        
        if type(tree) is ast.BinaryExpression:
            self.gen_expr(tree.expr1)
            self.gen_expr(tree.expr2)           
            op = tree.oper
            if op == '+':
                self.code.append((BINARY_ADD, None))
            elif op == '-':
                self.code.append((BINARY_SUBTRACT, None))
            elif op == '*':
                self.code.append((BINARY_MULTIPLY, None))
            elif op == '/':
                self.code.append((BINARY_DIVIDE, None))
            elif op == '>':
                self.code.append((COMPARE_OP, '>'))
            elif op == '<':
                self.code.append((COMPARE_OP, '<'))
            elif op == '=':
                self.code.append((COMPARE_OP, '=='))
            elif op == '\\':
                self.code.append((BINARY_MODULO, None))
            else:
                raise CodeGenError(op)
        
        elif type(tree) is ast.VnameExpression:
            self.code.append((LOAD_FAST, tree.variable.identifier))
        
        elif type(tree) is ast.IntegerExpression:
            self.code.append((LOAD_CONST, tree.value))
        
        elif type(tree) is ast.UnaryExpression:
            self.gen_expr(tree.expression)
            op = tree.operator
            if op == '+':
                self.code.append((BINARY_ADD, None))
            elif op == '-':
                self.code.append((BINARY_SUBTRACT, None))
            elif op == '*':
                self.code.append((BINARY_MULTIPLY, None))
            elif op == '/':
                self.code.append((BINARY_DIVIDE, None))
            elif op == '>':
                self.code.append((COMPARE_OP, '>'))
            elif op == '<':
                self.code.append((COMPARE_OP, '<'))
            elif op == '=':
                self.code.append((COMPARE_OP, '='))
            elif op == '\\':
                self.code.append((BINARY_MODULO, None))    
            else:
                raise CodeGenError(op)
        else:
            raise CodeGenError(tree)
            

    def gen_decl(self, tree):

        if type(tree) is ast.SequentialDeclaration:
            self.gen_decl(tree.decl1)
            self.gen_decl(tree.decl2)
        
        elif type(tree) is ast.ConstDeclaration:
            self.gen_expr(tree.expression)
            self.code.append((STORE_FAST, tree.identifier))
        
        elif type(tree) is ast.VarDeclaration:
            pass

        else:
            raise CodeGenError(tree)
    
    
def write_pyc_file(code, name):                                      
        pyc_file = name + '.pyc'
        print pyc_file
        with open(pyc_file,'wb') as pyc_f:
            magic = 0x03f30d0a
            pyc_f.write(struct.pack(">L",magic))
            pyc_f.write(struct.pack(">L",time.time()))
            marshal.dump(code.func_code, pyc_f)
            
               
if __name__ == '__main__':

    f = open(sys.argv[1])
    expr = f.read()
    f.close()
       
    s = scanner.Scanner(expr)
    tokens = s.scan()
    p = parser.Parser(tokens)
    tree = p.parse()
    
    print tree
    
    cg = CodeGen(tree)
    code = cg.generate()
    
    name = sys.argv[1].split('.')
    write_pyc_file(code, name[0])