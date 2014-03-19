#!/usr/bin/env python
# Author:    Jourdan Bul-lalayao
# Purpose:   Parser for Mini-Triangle Language. Reads in tokens that were scanned from
#            scanner.py, determines what tokens are valid,  and generates an abstract 
#            syntax tree out of tokens.

import scanner
import ast

class ParserError(Exception):
    """ Parser error exception.

        pos: position in the input token stream where the error occurred.
        type: bad token type
    """

    def __init__(self, pos, type):
        self.pos = pos
        self.type = type

    def __str__(self):
        return '(Found bad token %s at %d)' % (scanner.TOKENS[self.type], self.pos)
    

class Parser(object):
    
    """ 
    Program ::= Command
    Command ::= single-Command | (single-Command ';' single-Command)
    Declaration::= single-Declaration(';', single-Declaration)?
    Single-Declaration ::= const Identifier ~ expression | var Identifier : Type-Denoter
    Type-Denoter ::= Identifier
    Identifier ::= Letter(Letter | Digit)* | Digit(Letter | Digit)*
    Expression ::= primary-Expression(operator primary-Expression)*
    primary-Expression ::= int-literal | Vname | operator primary-expression | '(' Expression ')' """
    
    def __init__(self, tokens):
        self.tokens = tokens
        self.curindex = 0
        self.curtoken = tokens[0]
        self.temp_ast = 0
    
    def token_current(self):
        return self.curtoken
    
    def token_next(self):
        return self.tokens[self.curindex + 1]
    
    def token_accept_any(self):
        # Do not increment curindex if curtoken is TK_EOT.
        if self.curtoken.type != scanner.TK_EOT:
            self.curindex += 1
            self.curtoken = self.tokens[self.curindex]

    def token_accept(self, type):
        if self.curtoken.type != type:
            raise ParserError(self.curtoken.pos, self.curtoken.type)
        self.token_accept_any()
    
    
    def parse(self):
        e1 = self.parse_program()
        return e1
    
    def parse_program(self):
        """ Program :== Command """
        e1 = self.parse_command()
        return ast.Program(e1)
    
    def parse_command(self):
        """ Command :== single-Command (';' single-Command)* """ 
        e1 = self.parse_single_command()   
        token = self.token_current()
        
        if (token.type != scanner.TK_SEMICOLON):
            raise ParserError(self.curtoken.pos, self.curtoken.type)
        
        while (token.type == scanner.TK_SEMICOLON):
            self.token_accept(scanner.TK_SEMICOLON)
            token = self.token_current()
            
            ### just added
            if (token.type == scanner.TK_IDENTIFIER or
                token.type == scanner.TK_IF or
                token.type == scanner.TK_WHILE or
                token.type == scanner.TK_BEGIN or
                token.type == scanner.TK_LET):
                e2 = self.parse_single_command()
                token = self.token_current()
                e1 = ast.SequentialCommand(e1, e2)
            
                if (token.type != scanner.TK_SEMICOLON):
                    raise ParserError(self.curtoken.pos, self.curtoken.type)
            
            elif (token.type == scanner.TK_SEMICOLON):
                raise ParserError(self.curtoken.pos, self.curtoken.type)
  
        return e1   
            
    def parse_single_command(self):
        """ single-Command     ::=  V-name ':=' Expression
                    |   Identifier '(' Expression ')'
                    |   if Expression then single-Command
                           else single-Command
                    |   while Expression do single-Command
                    |   let Declaration in single-Command
                    |   begin Command end """
                    
        token = self.token_current()
        
        if token.type == scanner.TK_IDENTIFIER:
            temp = token.val
            self.token_accept(scanner.TK_IDENTIFIER)
            token = self.token_current()
            
            if token.type == scanner.TK_BECOMES:
                self.token_accept(scanner.TK_BECOMES)
                vname = ast.Vname(temp)
                e1 = self.parse_expr()
                token = self.token_current()
                return ast.AssignCommand(vname, e1)
             
            if token.type == scanner.TK_LPAREN:
                self.token_accept(scanner.TK_LPAREN)
                e1 = self.parse_expr()
                self.token_accept(scanner.TK_RPAREN)
                return ast.CallCommand(temp, e1)
            
            else: #just added
                raise ParserError(self.curtoken.pos, self.curtoken.type)
            
        if token.type == scanner.TK_IF: 
            self.token_accept(scanner.TK_IF)
            e1 = self.parse_expr()
            token = self.token_current()
            
            if token.type == scanner.TK_THEN:
                self.token_accept(scanner.TK_THEN)
                e2 = self.parse_single_command()
                token = self.token_current()
                
                if token.type == scanner.TK_ELSE:
                    self.token_accept(scanner.TK_ELSE)
                    e3 = self.parse_single_command()
                    token = self.token_current()
                    return ast.IfCommand(e1, e2, e3)
                
                else:
                    raise ParserError(self.curtoken.pos, self.curtoken.type)
            
            else: 
                raise ParserError(self.curtoken.pos, self.curtoken.type)
            
        
        if token.type == scanner.TK_WHILE:
            self.token_accept_any()
            e1 = self.parse_expr()
            token = self.token_current()

            if token.type == scanner.TK_DO:
                self.token_accept_any()
                e2 = self.parse_single_command()
                token = self.token_current()
                return ast.WhileCommand(e1, e2)
            else:
                raise ParserError(self.curtoken.pos, self.curtoken.type)
            
        
        if token.type == scanner.TK_BEGIN:
            self.token_accept(scanner.TK_BEGIN)

            while token.type != scanner.TK_END:       
                e1 = self.parse_command()
                token = self.token_current()
        
            if token.type == scanner.TK_END:
                self.token_accept_any()
                token = self.token_current()
            return e1
        
        if token.type == scanner.TK_LET:
            e1 = self.parse_let_command()
            return e1
    
        else: #just added
            raise ParserError(self.curtoken.pos, self.curtoken.type)
        
    def parse_expr(self):
        """ Expression ::= primary-Expression (operator primary-Expression)* """
        e1 = self.parse_pri_expr()
        token = self.token_current()
        
        while (token.type == scanner.TK_OPERATOR):
            op = token.val
            self.token_accept(scanner.TK_OPERATOR)
            e2 = self.parse_pri_expr()
            token = self.token_current() 
            e1 = ast.BinaryExpression(e1, op, e2)  

        return e1
    
    
    def parse_pri_expr(self):
        """ primary-Expression ::= Integer-Literal
                    |   V-name (or identifier)
                    |   Operator primary-Expression
                    |   '(' Expression ')' """
        
        token = self.token_current()
            
        if (token.type == scanner.TK_INTLITERAL):
            self.token_accept(scanner.TK_INTLITERAL)
            return ast.IntegerExpression(token.val)
            
        if (token.type == scanner.TK_IDENTIFIER):
            v = ast.Vname(token.val)
            self.token_accept(scanner.TK_IDENTIFIER)           
            return ast.VnameExpression(v)

        if (token.type == scanner.TK_LPAREN):
            self.token_accept(scanner.TK_LPAREN)
            e1 = self.parse_expr()
            self.token_accept(scanner.TK_RPAREN)
            return e1
        
        else: #just added
            raise ParserError(self.curtoken.pos, self.curtoken.type)
        
            
    def parse_let_command(self):
        self.token_accept(scanner.TK_LET)
        token = self.token_current()
        
        if (token.type == scanner.TK_CONST or token.type == scanner.TK_VAR): 
            e1 = self.parse_declaration()
            token = self.token_current()

            while (token.type == scanner.TK_SEMICOLON):
                self.token_accept(scanner.TK_SEMICOLON)
                
                token = self.token_current()
                if (token.type == scanner.TK_IN):
                    break
                
                e2 = self.parse_declaration()
                token = self.token_current()
                e1 = ast.SequentialDeclaration(e1, e2)
                
                if (token.type != scanner.TK_SEMICOLON):
                    raise ParserError(self.curtoken.pos, self.curtoken.type)
            
            if (token.type == scanner.TK_IN):
                self.token_accept(scanner.TK_IN)
                e2 = self.parse_single_command()
                return ast.LetCommand(e1, e2)
            
            else: #just added
                raise ParserError(self.curtoken.pos, self.curtoken.type)

        else:
            raise ParserError(self.curtoken.pos, self.curtoken.type)
    
    def parse_declaration(self):
        token = self.token_current()
        
        if (token.type == scanner.TK_VAR):
            self.token_accept(scanner.TK_VAR)
            token = self.token_current()
            
            if (token.type == scanner.TK_IDENTIFIER):
                self.token_accept(scanner.TK_IDENTIFIER)
                self.token_accept(scanner.TK_COLON)
                e1 = self.parse_type_denoter()
                return ast.VarDeclaration(token.val, e1)
            
            else:
                raise ParserError(self.curtoken.pos, self.curtoken.type)
            
        if (token.type == scanner.TK_CONST):
            
            self.token_accept_any()
            token = self.token_current()
        
            if (token.type == scanner.TK_IDENTIFIER):
                self.token_accept_any()
                self.token_accept(scanner.TK_IS)
                e1 = self.parse_expr()
                return ast.ConstDeclaration(token.val, e1)
        
            else:
                raise ParserError(self.curtoken.pos, self.curtoken.type)
            
        else: #just added
            raise ParserError(self.curtoken.pos, self.curtoken.type)
            
            
    def parse_var_declaration(self):
        self.token_accept_any()
        token = self.token_current()
        
        if (token.type == scanner.TK_IDENTIFIER):
            self.token_accept_any()
            self.token_accept(scanner.TK_COLON)
            e1 = self.parse_type_denoter()
            return ast.VarDeclaration(token.val, e1)
        
        else:
            raise ParserError(self.curtoken.pos, self.curtoken.type)
    
    
    def parse_const_declaration(self):
        self.token_accept_any()
        token = self.token_current()
        
        if (token.type == scanner.TK_IDENTIFIER):
            self.token_accept_any()
            self.token_accept(scanner.TK_IS)
            e1 = self.parse_expr()
            return ast.ConstDeclaration(token.val, e1)
        
        else:
            raise ParserError(self.curtoken.pos, self.curtoken.type)
    
    
    def parse_type_denoter(self):
        token = self.token_current()
        
        if token.type == scanner.TK_IDENTIFIER:
            self.token_accept(scanner.TK_IDENTIFIER)
            return ast.TypeDenoter(token.val)
        else:
            raise ParserError(self.curtoken.pos, self.curtoken.type)
    
        
if __name__ == '__main__':
    expr = ["""
let var x: Integer
in 
    x := 0
"""]
    
    expr2 = ["""
! This is a comment.
let
    const m ~ 7;
    var n: Integer
in
    begin
        n := 2 * m * m;
        putint(n)
    end
"""]
    
    expr3 = ["""
! Factorial
let var x: Integer;
    var fact: Integer
in
  begin
    getint(x);
    fact := 1;
    while x > 0 do
      begin
        fact := fact * x;
        x := x - 1
      end;
    putint(fact)
  end
"""]
    
    expr4 = ["""let
                   var x: Integer;
                   var y: Integer;
                   var z: Integer
                 in
                   begin
                     getint(x);
                     y := 2;
                     z := x + y;
                     putint(z)
                   end
              """,
              """! Factorial
                 let var x: Integer;
                     var fact: Integer
                 in
                   begin
                     getint(x);
                     fact := 1;
                     while x > 0 do
                       begin
                         fact := fact * x;
                         x := x - 1
                       end;
                     putint(fact)
                   end
              """]
    
    expr5 = ["""! Factorial
let var x: Integer;
   var fact: Integer
in
  begin
    getint(x);
    if x = 0 then
      putint(1)
    else
      begin
        fact := 1;
        while x > 0 do
          begin
            fact := fact * x;
            x := x - 1
          end;
        putint(fact)
      end
  end
"""]
    for exp in expr5:
        print '=============='
        print exp
        
        s = scanner.Scanner(exp)
        
        try:
            tokens = s.scan()
            print tokens
        except s.ScannerError as e:
            print e
            continue

        parser = Parser(tokens)

        try:
            tree = parser.parse()
            print tree
        except ParserError as e:
            print e
            print 'Not Parsed!'
            continue

        print 'Parsed!'
