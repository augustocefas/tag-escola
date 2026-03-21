export function replacePlaceholders(
    template: string,
    data: Record<string, any>,
): string {
    return template.replace(/\{(\w+)\}/g, (match, key) => {
        // Se a chave existe no objeto, retorna o valor
        if (key in data && data[key] !== undefined && data[key] !== null) {
            return String(data[key]);
        }
        // Se não existe, mantém o placeholder original
        return match;
    });
}

export function replacePlaceholdersStrict(
    template: string,
    data: Record<string, any>,
    strict: boolean = true,
): string {
    return template.replace(/\{(\w+)\}/g, (match, key) => {
        if (key in data && data[key] !== undefined && data[key] !== null) {
            return String(data[key]);
        }

        if (strict) {
            throw new Error(`Placeholder {${key}} not found in data object`);
        }

        return match;
    });
}

/**
 * Verifica se uma string contém placeholders no formato {key}
 *
 * @param template - String para verificar
 * @returns true se contém placeholders, false caso contrário
 *
 * @example
 * hasPlaceholders('/user/{id}/profile')
 * // retorna: true
 *
 * @example
 * hasPlaceholders('/user/profile')
 * // retorna: false
 */
export function hasPlaceholders(template: string): boolean {
    return /\{(\w+)\}/g.test(template);
}

/**
 * Extrai todos os nomes de placeholders de uma string
 *
 * @param template - String com placeholders
 * @returns Array com os nomes dos placeholders encontrados
 *
 * @example
 * extractPlaceholders('/user/{id}/posts/{postId}')
 * // retorna: ['id', 'postId']
 */
export function extractPlaceholders(template: string): string[] {
    const matches = template.matchAll(/\{(\w+)\}/g);
    return Array.from(matches, (match) => match[1]);
}

export function capitalizeFirstLetter(text: string): string {
    if (!text || typeof text !== 'string') return '';
    const lower = text.toLowerCase();
    return lower.charAt(0).toUpperCase() + lower.slice(1);
}

export function toUpperCaseSafe(text: string): string {
    if (!text || typeof text !== 'string') return '';
    return text.toUpperCase();
}

export function getObjectValue(
    obj: Record<string, string>,
    key: string,
): string {
    if (!obj || !key) return capitalizeFirstLetter(key);
    return obj[key.toLowerCase()] ?? capitalizeFirstLetter(key);
}
export function returnDayDD(date: string): string {
    const day = new Date(date);
    return day.getDate().toString().padStart(2, '0');
}
export function returnMonthText(date: string, abrev: boolean = true): string {
    const month = new Date(date);
    return month.toLocaleDateString('pt-BR', {
        month: abrev ? 'short' : 'long',
    });
}
export function returnTimeHHMM(date: string): string {
    return date.split(':')[0] + ':' + date.split(':')[1];
}
/**
 * Converte uma diferença de tempo em texto amigável
 * @param {Date|string} dateTime - Data para comparar com o momento atual
 * @param {Object} options - Opções de configuração
 * @returns {string} - Texto formatado (ex: "2 horas", "5 dias", "1 semana")
 */
export function getTimeAgo(
    dateTime: string | Date,
    options: { future?: boolean; short?: boolean; now?: Date } = {
        future: false,
        short: false,
        now: new Date(),
    },
) {
    const { future, short, now } = options;

    // Converte para objeto Date se for string
    const date = new Date(dateTime);
    if (isNaN(date.getTime())) {
        throw new Error('Data inválida');
    }

    // Calcula diferença em milissegundos
    const diffMs = date.getTime() - (now?.getTime() || new Date().getTime());
    const absDiffMs = Math.abs(diffMs);

    // Define se é passado ou futuro
    const isPast = diffMs < 0;
    const prefix = !future ? '' : isPast ? 'há ' : 'em ';
    const suffix = future ? '' : isPast ? ' atrás' : '';

    // Calcula diferenças em diferentes unidades
    const seconds = Math.floor(absDiffMs / 1000);
    const minutes = Math.floor(seconds / 60);
    const hours = Math.floor(minutes / 60);
    const days = Math.floor(hours / 24);
    const weeks = Math.floor(days / 7);
    const months = Math.floor(days / 30);
    const years = Math.floor(days / 365);

    // Arrays com nomes das unidades
    const units = {
        second: { singular: 'segundo', plural: 'segundos', short: 's' },
        minute: { singular: 'minuto', plural: 'minutos', short: 'min' },
        hour: { singular: 'hora', plural: 'horas', short: 'h' },
        day: { singular: 'dia', plural: 'dias', short: 'd' },
        week: { singular: 'semana', plural: 'semanas', short: 'sem' },
        month: { singular: 'mês', plural: 'meses', short: 'm' },
        year: { singular: 'ano', plural: 'anos', short: 'a' },
    };

    let value, unit;

    // Define a melhor unidade baseada no tempo
    if (years >= 1) {
        value = years;
        unit = units.year;
    } else if (months >= 1) {
        value = months;
        unit = units.month;
    } else if (weeks >= 1) {
        value = weeks;
        unit = units.week;
    } else if (days >= 1) {
        value = days;
        unit = units.day;
    } else if (hours >= 1) {
        value = hours;
        unit = units.hour;
    } else if (minutes >= 1) {
        value = minutes;
        unit = units.minute;
    } else {
        value = seconds;
        unit = units.second;
    }

    // Para segundos, mostra "agora" se for muito recente
    if (unit === units.second && value < 30 && !future) {
        return 'agora';
    }

    // Formata o texto
    if (short) {
        return `${prefix}${value}${unit.short}`;
    } else {
        const unitName = value === 1 ? unit.singular : unit.plural;
        return `${prefix}${value} ${unitName}${suffix}`.trim();
    }
}

// Versão simplificada para uso comum
export function timeAgo(dateTime: string | Date) {
    return getTimeAgo(dateTime, { future: false });
}

// Versão para tempo futuro
export function timeUntil(dateTime: string | Date) {
    return getTimeAgo(dateTime, { future: true });
}

// Exemplo prático com sua lista de pendências
export function formatDueDate(dataPagamento: string | Date) {
    const hoje = new Date();
    const vencimento = new Date(dataPagamento);

    if (vencimento < hoje) {
        return `Vencido ${timeAgo(vencimento)}`;
    } else {
        return `Vence ${timeUntil(vencimento)}`;
    }
}

export function nNull(value: any, defaultValue: any = '-') {
    return value && value!=null ? value : defaultValue;
}

export function mascaraCPFCNPJ(cpfCnpj?: string): string {
    if (!cpfCnpj || cpfCnpj == null) return '';
    const apenasNumeros = cpfCnpj.replace(/\D/g, '');
    if (apenasNumeros.length !== cpfCnpj.replace(/[\.\-\/]/g, '').length) {
        return cpfCnpj;
    }
    if (apenasNumeros.length === 11) {
        return apenasNumeros.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
    } else {
        return apenasNumeros.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
    }
}

export function calculaIdade(dataNascimentoBr: string): string {
    const hoje = new Date();
    const nascimento = new Date(dataNascimentoBr);
    let idade = hoje.getFullYear() - nascimento.getFullYear();
    const mesHoje = hoje.getMonth();
    const mesNascimento = nascimento.getMonth();
    if (mesHoje < mesNascimento || (mesHoje === mesNascimento && hoje.getDate() < nascimento.getDate())) {
        idade--;
    }
    return idade.toString();
}

export function suportaAssinatura(format: string): boolean {
    return format === 'pdf';
}