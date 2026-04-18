/**
 * Mercator Query Engine — Parser SQL-like
 * Exposé via window.MercatorQuery.parse() et window.MercatorQuery.dslToSql()
 *
 * Syntaxe supportée :
 *   FROM <Model>
 *   [FIELDS <field>, <relation.field>, ...]
 *   [WHERE <conditions>]
 *   [WITH <relation>, <relation.relation>, ...]
 *   [DEPTH <n>]
 *   [OUTPUT graph|list]
 *   [LIMIT <n>]
 */

(function () {
    'use strict';

    // ─────────────────────────────────────────────────────────────
    // Tokenizer
    // ─────────────────────────────────────────────────────────────

    const KEYWORDS = new Set([
        'FROM', 'WHERE', 'AND', 'OR', 'NOT', 'WITH', 'TRAVERSE',
        'OUTPUT', 'LIMIT', 'LIKE', 'IN', 'IS',
        'FIELDS', 'SELECT', 'NULL', 'TRUE', 'FALSE',
    ]);

    function tokenize(input) {
        const tokens = [];
        let i = 0;

        while (i < input.length) {

            // Espaces
            if (/\s/.test(input[i])) {
                i++;
                continue;
            }

            // Commentaires --
            if (input[i] === '-' && input[i + 1] === '-') {
                while (i < input.length && input[i] !== '\n') i++;
                continue;
            }

            // Chaînes "..." ou '...'
            if (input[i] === '"' || input[i] === "'") {
                const q = input[i++];
                let s = '';
                while (i < input.length && input[i] !== q) {
                    if (input[i] === '\\') i++;
                    s += input[i++];
                }
                i++;
                tokens.push({type: 'STRING', value: s});
                continue;
            }

            // Nombres
            const prev = tokens[tokens.length - 1];
            const canBeNeg = input[i] === '-'
                && /[0-9]/.test(input[i + 1])
                && (!prev || ['COMMA', 'LPAREN', 'LBRACKET', 'OP'].includes(prev.type));

            if (/[0-9]/.test(input[i]) || canBeNeg) {
                let n = '';
                if (input[i] === '-') n += input[i++];
                while (i < input.length && /[0-9.]/.test(input[i])) n += input[i++];
                tokens.push({type: 'NUMBER', value: parseFloat(n)});
                continue;
            }

            // Opérateurs 2 caractères
            const two = input.substring(i, i + 2);
            if (['>=', '<=', '!=', '<>'].includes(two)) {
                tokens.push({type: 'OP', value: two === '<>' ? '!=' : two});
                i += 2;
                continue;
            }

            // Opérateurs 1 caractère
            if ('=<>'.includes(input[i])) {
                tokens.push({type: 'OP', value: input[i]});
                i++;
                continue;
            }

            // Ponctuation
            const punct = {'(': 'LPAREN', ')': 'RPAREN', '[': 'LBRACKET', ']': 'RBRACKET', ',': 'COMMA'};
            if (punct[input[i]]) {
                tokens.push({type: punct[input[i]]});
                i++;
                continue;
            }

            // Identifiants et mots-clés
            if (/[a-zA-Z_]/.test(input[i])) {
                let id = '';
                while (i < input.length && /[a-zA-Z0-9_.]/.test(input[i])) id += input[i++];
                const up = id.toUpperCase();
                if (KEYWORDS.has(up)) tokens.push({type: up});
                else if (up === 'TRUE') tokens.push({type: 'BOOLEAN', value: true});
                else if (up === 'FALSE') tokens.push({type: 'BOOLEAN', value: false});
                else tokens.push({type: 'IDENT', value: id});
                continue;
            }

            i++;
        }

        tokens.push({type: 'EOF'});
        return tokens;
    }

    // ─────────────────────────────────────────────────────────────
    // Parser (descente récursive)
    // ─────────────────────────────────────────────────────────────

    class Parser {
        constructor(tokens) {
            this.tokens = tokens;
            this.current = 0;
        }

        peek() {
            return this.tokens[this.current];
        }

        advance() {
            return this.tokens[this.current++];
        }

        check(type) {
            return this.peek().type === type;
        }

        match(...types) {
            if (types.includes(this.peek().type)) return this.advance();
            return null;
        }

        expect(type) {
            if (!this.check(type))
                throw new Error(`Attendu "${type}", obtenu "${this.peek().type}" (valeur: "${this.peek().value ?? ''}")`);
            return this.advance();
        }

        parse() {
            const dsl = {};

            this.expect('FROM');
            dsl.from = this.expect('IDENT').value;

            while (!this.check('EOF')) {
                if (this.match('WHERE')) dsl.filters = this.parseWhereClause();
                else if (this.match('WITH', 'TRAVERSE')) dsl.traverse = this.parseIdentList();
                else if (this.match('FIELDS', 'SELECT')) dsl.fields = this.parseIdentList();
                else if (this.match('OUTPUT')) dsl.output = this.expect('IDENT').value.toLowerCase();
                else if (this.match('LIMIT')) dsl.limit = this.expect('NUMBER').value;
                else throw new Error(`Clause inconnue : "${this.peek().value ?? this.peek().type}"`);
            }

            return dsl;
        }

        parseIdentList() {
            const items = [this.expect('IDENT').value];
            while (this.match('COMMA')) items.push(this.expect('IDENT').value);
            return items;
        }

        parseWhereClause() {
            const result = this.parseOrExpr();
            return Array.isArray(result) ? result : [result];
        }

        parseOrExpr() {
            let left = this.parseAndExpr();
            while (this.match('OR')) {
                const right = this.parseAndExpr();
                left = this.buildOrGroup(left, right);
            }
            return left;
        }

        parseAndExpr() {
            let left = this.parseUnary();
            while (this.match('AND')) {
                const right = this.parseUnary();
                left = this.buildAndGroup(left, right);
            }
            return left;
        }

        parseUnary() {
            if (this.match('NOT')) {
                const inner = this.parseUnary();
                return {boolean: 'not', group: Array.isArray(inner) ? inner : [inner]};
            }
            if (this.match('LPAREN')) {
                const inner = this.parseOrExpr();
                this.expect('RPAREN');
                const group = Array.isArray(inner) ? inner : [inner];
                return {boolean: 'and', group};
            }
            return this.parseCondition();
        }

        buildAndGroup(left, right) {
            const leftArr = Array.isArray(left) ? left : [left];
            const rightArr = Array.isArray(right) ? right : [right];
            return [...leftArr, ...rightArr];
        }

        buildOrGroup(left, right) {
            const leftArr = Array.isArray(left) ? left : [left];
            const rightItem = Array.isArray(right)
                ? {boolean: 'or', group: right}
                : {...right, boolean: 'or'};
            return [...leftArr, rightItem];
        }

        parseCondition() {
            const field = this.expect('IDENT').value;

            // IS NULL / IS NOT NULL
            if (this.match('IS')) {
                if (this.match('NOT')) {
                    this.expect('NULL');
                    return {field, operator: '!=', value: null};
                }
                this.expect('NULL');
                return {field, operator: '=', value: null};
            }

            // LIKE
            if (this.match('LIKE')) return {field, operator: 'like', value: this.parseScalar()};

            // NOT LIKE / NOT IN
            if (this.match('NOT')) {
                if (this.match('LIKE')) return {field, operator: 'not like', value: this.parseScalar()};
                this.expect('IN');
                return {field, operator: 'not in', value: this.parseArray()};
            }

            // IN
            if (this.match('IN')) return {field, operator: 'in', value: this.parseArray()};

            // Opérateur standard
            const op = this.expect('OP').value;
            return {field, operator: op, value: this.parseScalar()};
        }

        parseScalar() {
            const tok = this.peek();
            if (['STRING', 'NUMBER', 'BOOLEAN'].includes(tok.type)) {
                this.advance();
                return tok.value;
            }
            if (tok.type === 'NULL') {
                this.advance();
                return null;
            }
            if (tok.type === 'IDENT') {
                this.advance();
                return tok.value;
            }
            throw new Error(`Valeur attendue, obtenu : "${tok.type}"`);
        }

        parseArray() {
            const open = this.check('LPAREN') ? 'LPAREN' : 'LBRACKET';
            const close = open === 'LPAREN' ? 'RPAREN' : 'RBRACKET';
            this.expect(open);
            const values = [this.parseScalar()];
            while (this.match('COMMA')) values.push(this.parseScalar());
            this.expect(close);
            return values;
        }
    }

    // ─────────────────────────────────────────────────────────────
    // DSL → SQL-like
    // ─────────────────────────────────────────────────────────────

    function dslToSql(dsl) {
        if (!dsl || !dsl.from) return '';
        const lines = [];

        lines.push(`FROM ${dsl.from}`);
        if (dsl.fields?.length) lines.push(`FIELDS ${dsl.fields.join(', ')}`);
        if (dsl.filters?.length) lines.push(`WHERE ${filtersToSql(dsl.filters, '')}`);
        if (dsl.traverse?.length) lines.push(`WITH ${dsl.traverse.join(', ')}`);
        if (dsl.output != null) lines.push(`OUTPUT ${dsl.output}`);
        if (dsl.limit != null) lines.push(`LIMIT ${dsl.limit}`);

        return lines.join('\n');
    }

    function filtersToSql(filters, indent) {
        if (!Array.isArray(filters)) filters = [filters];

        return filters.map((f, i) => {
            const bool = (f.boolean ?? (i === 0 ? 'and' : 'and')).toUpperCase();
            const prefix = i === 0 ? '' : `${bool} `;

            if (f.group) {
                const inner = filtersToSql(f.group, indent + '    ');
                return `${prefix}(\n${indent}    ${inner}\n${indent})`;
            }

            const val = fmtVal(f.value);
            const op = (f.operator ?? '').toUpperCase();

            if (f.operator === 'like') return `${prefix}${f.field} LIKE ${val}`;
            if (f.operator === 'not like') return `${prefix}${f.field} NOT LIKE ${val}`;
            if (f.operator === 'in') return `${prefix}${f.field} IN (${f.value.map(fmtVal).join(', ')})`;
            if (f.operator === 'not in') return `${prefix}${f.field} NOT IN (${f.value.map(fmtVal).join(', ')})`;
            if (f.value === null && f.operator === '=') return `${prefix}${f.field} IS NULL`;
            if (f.value === null && f.operator === '!=') return `${prefix}${f.field} IS NOT NULL`;

            return `${prefix}${f.field} ${op} ${val}`;
        }).join(`\n${indent}`);
    }

    function fmtVal(v) {
        if (v === null) return 'NULL';
        if (typeof v === 'boolean') return v ? 'TRUE' : 'FALSE';
        if (typeof v === 'number') return String(v);
        return `"${v}"`;
    }

    // ─────────────────────────────────────────────────────────────
    // Exposition globale
    // ─────────────────────────────────────────────────────────────

    window.MercatorQuery = {
        parse(input) {
            const parser = new Parser(tokenize(input));
            return parser.parse();
        },
        dslToSql,
    };

})();