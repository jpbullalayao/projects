#!/usr/bin/env python
# Author:    Jourdan Bul-lalayao
# Purpose:   Scanner for Mini Triangle. Scans tokens of entire input file for parser2.py to
#            parse.

import cStringIO as StringIO
import string

# Token Constants

TK_IDENTIFIER = 0
TK_INTLITERAL = 1
TK_OPERATOR   = 2
TK_BEGIN      = 3  # begin
TK_CONST      = 4  # const
TK_DO         = 5  # do
TK_ELSE       = 6  # else
TK_END        = 7  # end
TK_IF         = 8  # if
TK_IN         = 9  # in
TK_LET        = 10 # let
TK_THEN       = 11 # then
TK_VAR        = 12 # var
TK_WHILE      = 13 # while
TK_SEMICOLON  = 14 # ;
TK_COLON      = 15 # :
TK_BECOMES    = 16 # :=
TK_IS         = 17 # ~
TK_LPAREN     = 18 # (
TK_RPAREN     = 19 # )
TK_EOT        = 20 # end of text

TOKENS = {TK_IDENTIFIER: 'IDENTIFIER',
          TK_INTLITERAL: 'INTLITERAL',
          TK_OPERATOR:   'OPERATOR',
          TK_BEGIN:      'BEGIN',
          TK_CONST:      'CONST',
          TK_DO:         'DO',
          TK_ELSE:       'ELSE',
          TK_END:        'END',
          TK_IF:         'IF',
          TK_IN:         'IN',
          TK_LET:        'LET',
          TK_THEN:       'THEN',
          TK_VAR:        'VAR',
          TK_WHILE:      'WHILE',
          TK_SEMICOLON:  'SEMICOLON',
          TK_COLON:      'COLON',
          TK_BECOMES:    'BECOMES',
          TK_IS:         'IS',
          TK_LPAREN:     'LPAREN',
          TK_RPAREN:     'RPAREN',
          TK_EOT:        'EOT'}

class Token(object):
    """ A simple Token structure.
        
        Contains the token type, value and position. 
    """
    def __init__(self, type, val, pos):
        self.type = type
        self.val = val
        self.pos = pos

    def __str__(self):
        return '(%s(%s) at %s)' % (TOKENS[self.type], self.val, self.pos)

    def __repr__(self):
        return self.__str__()


class ScannerError(Exception):
    """ Scanner error exception.

        pos: position in the input text where the error occurred.
    """
    def __init__(self, pos, char):
        self.pos = pos
        self.char = char

    def __str__(self):
        return 'ScannerError at pos = %d, char = %s' % (self.pos, self.char)

class Scanner(object):
    
    """Implement a scanner for the following token grammar
    
       Token     :== EOT | Int | '(' | ')' | Op
       Int       :== Digit (Digit*)
       Op        :== '+' | '-' | '*' | '/' 
       Digit     :== [0..9]

       Separator :== ' ' | '\t' | '\n'        
    """

    def __init__(self, input):
        # Use StringIO to treat input string like a file.
        self.inputstr = StringIO.StringIO(input)
        self.eot = False   # Are we at the end of the input text?
        self.pos = 0       # Position in the input text
        self.char = ''     # The current character from the input text
        self.char_take()   # Fill self.char with the first character

    def scan(self):
        """Main entry point to scanner object.

        Return a list of Tokens.
        """
        self.tokens = []
        while 1:
            token = self.scan_token()
            self.tokens.append(token)
            if token.type == TK_EOT:
                break
        return self.tokens
    
    def scan_int(self):
        """Int :== Digit (Digit*)"""
        
        pos = self.char_pos()
        numlist = [self.char_take()]

        while self.char_current().isdigit():
            numlist.append(self.char_take())
        
        return Token(TK_INTLITERAL, int(string.join(numlist ,'')), pos)
    
    def scan_identifier(self, word_pos, value):
        
        """Identifier :== Letter | Identifier Letter | Identifier Digit"""
        return Token(TK_IDENTIFIER, string.join(value, ''),word_pos)
    
    def scan_alpha(self):
        
        value = []
        word_pos = self.char_pos()
        while (self.char_current().isspace() == False and not self.char_eot() and (self.char_current().isalpha() or self.char_current().isdigit())):                
            value.append(self.char_current())
            self.char_take()
                
        if (string.join(value, '') == 'begin'):
            token = Token(TK_BEGIN, 0, word_pos)
                
        elif (string.join(value,'') == 'if'):
            token = Token(TK_IF, 0, word_pos)
                
        elif (string.join(value, '') == 'const'):
            token = Token(TK_CONST, 0, word_pos)
                
        elif (string.join(value, '') == 'do'):
            token = Token(TK_DO, 0, word_pos)
                
        elif (string.join(value, '') == 'end'):
            token = Token(TK_END, 0, word_pos)
                
        elif (string.join(value, '') == 'else'):
            token = Token(TK_ELSE, 0, word_pos)
                
        elif (string.join(value, '') == 'in'):
            token = Token(TK_IN, 0, word_pos)
                
        elif (string.join(value, '') == 'let'):
            token = Token(TK_LET, 0, word_pos)
                
        elif (string.join(value, '') == 'then'):
            token = Token(TK_THEN, 0, word_pos)
                
        elif (string.join(value, '') == 'var'):
            token = Token(TK_VAR, 0, word_pos)
                
        elif (string.join(value, '') == 'while'):
            token = Token(TK_WHILE, 0, word_pos)
                    
        else:
            token = self.scan_identifier(word_pos, value)     
            
        return token   
        
    def scan_token(self):
        """Scan a single token from input text."""

        c = self.char_current()
        token = None
        value = []
        
        OPERATORS = ['+', '-', '*', '/', '<', '>', '\\', '=']
        
        while not self.char_eot():
            # Whitespace
            if (c.isspace() or c == '\n'):
                self.char_take()
                c = self.char_current() 
                continue
            
            # Comment
            elif (c == '!'):
                while (self.char_current() != '\n' and not self.char_eot()):
                    self.char_take()
                c = self.char_current()
                continue
            
            # Colon or Becomes
            elif (c == ':'):
                char_pos = self.char_pos()
                
                while (self.char_current().isspace() == False and not self.char_eot() and self.char_current().isalpha() == False):
                    value.append(self.char_current())
                    self.char_take()
                
                if (string.join(value, '') == ':='):
                    token = Token(TK_BECOMES, 0, char_pos)
                
                elif (string.join(value, '') == ':'):
                    token = Token(TK_COLON, 0, char_pos)
                break
            
            # Is
            elif (c == '~'):
                token = Token(TK_IS, 0, self.char_pos())
                self.char_take()
                break
            
            # Left Parenthesis
            elif (c == '('):
                token = Token(TK_LPAREN, 0, self.char_pos())
                self.char_take() 
                break
            
            # Right Parenthesis
            elif (c == ')'):
                token = Token(TK_RPAREN, 0, self.char_pos())
                self.char_take()
                break

            # Integer-Literal 
            elif (c.isdigit()):
                token = self.scan_int()
                break
            
            # Operator
            elif (c in OPERATORS):
                token = Token(TK_OPERATOR, c, self.char_pos())
                self.char_take()
                break

            # Keyword or Identifier
            elif (c.isalpha()):    
                token = self.scan_alpha()
                c = self.char_current()
                break
            
            # Semicolon
            elif (c == ';'):
                token = Token(TK_SEMICOLON, 0, self.char_pos())
                self.char_take()
                break
            
            else:
                raise ScannerError(self.char_pos(), self.char_current())
      
        if token is not None:
            return token
           
        if self.char_eot():
            return(Token(TK_EOT, 0, self.char_pos()))
        
    def char_current(self):
        """Return in the current input character."""

        return self.char

    def char_take(self):
        """Consume the current character and read the next character 
        from the input text.

        Update self.char, self.eot, and self.pos
        """

        char_prev = self.char
        
        self.char = self.inputstr.read(1)
        if self.char == '':
            self.eot = True

        self.pos += 1
        
        return char_prev
        
    def char_pos(self):
        """Return the position of the *current* character in the input text."""

        return self.pos - 1
        
    def char_eot(self):
        """Determine if we are at the end of the input text."""

        return self.eot
        

if __name__ == '__main__':
    expr = ["""
! Factorial
let var x: Integer
    var fact: Integer
in
    begin
        fact := 1
        while x > 0:
            fact := fact * x
            x := x - 1
        putint(fact)
    end
"""]
    for exp in expr:
        print '=============='
        print exp
        scanner = Scanner(exp)
        try:
            tokens = scanner.scan()
            print tokens
        except ScannerError as e:
            print e