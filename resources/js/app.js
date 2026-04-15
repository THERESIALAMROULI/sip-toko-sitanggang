import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

const fieldSelectors = 'input, select, textarea';

const humanizeFieldName = (value) => {
    if (!value) {
        return 'Kolom ini';
    }

    return value
        .replace(/\[\]/g, '')
        .replace(/\[\d+\]/g, ' ')
        .replace(/\./g, ' ')
        .replace(/_/g, ' ')
        .replace(/\s+/g, ' ')
        .trim()
        .replace(/\b\w/g, (char) => char.toUpperCase());
};

const getFieldLabel = (field) => {
    const explicitLabel = field.dataset.label || field.getAttribute('aria-label');
    if (explicitLabel) {
        return explicitLabel.trim();
    }

    if (field.id) {
        const escapedId = window.CSS?.escape ? window.CSS.escape(field.id) : field.id;
        const label = document.querySelector(`label[for="${escapedId}"]`);
        if (label) {
            return label.textContent.replace(/[*:]/g, '').trim();
        }
    }

    const wrapperLabel = field.closest('label');
    if (wrapperLabel) {
        return wrapperLabel.textContent.replace(/[*:]/g, '').trim();
    }

    const placeholder = field.getAttribute('placeholder');
    if (placeholder) {
        return placeholder.replace(/\s*\(opsional\)\s*$/i, '').trim();
    }

    return humanizeFieldName(field.getAttribute('name') || field.id);
};

const formatConstraintValue = (value, type) => {
    if (!value) {
        return value;
    }

    if (type === 'date') {
        const parsedDate = new Date(`${value}T00:00:00`);
        if (!Number.isNaN(parsedDate.getTime())) {
            return new Intl.DateTimeFormat('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric',
            }).format(parsedDate);
        }
    }

    if (type === 'number' || type === 'range') {
        const numericValue = Number(value);
        if (!Number.isNaN(numericValue)) {
            return new Intl.NumberFormat('id-ID').format(numericValue);
        }
    }

    return value;
};

const getCustomValidityMessage = (field) => {
    const { validity } = field;
    const label = getFieldLabel(field);

    if (validity.valueMissing) {
        if (field instanceof HTMLSelectElement) {
            return `Silakan pilih ${label.toLowerCase()}.`;
        }

        if (field.type === 'checkbox' || field.type === 'radio') {
            return `Silakan pilih ${label.toLowerCase()}.`;
        }

        return `${label} wajib diisi.`;
    }

    if (validity.typeMismatch) {
        if (field.type === 'email') {
            return `${label} harus berupa alamat email yang valid.`;
        }

        if (field.type === 'url') {
            return `${label} harus berupa tautan yang valid.`;
        }

        return `Format ${label.toLowerCase()} tidak valid.`;
    }

    if (validity.badInput) {
        if (field.type === 'number' || field.type === 'range') {
            return `${label} harus berupa angka.`;
        }

        return `${label} tidak valid.`;
    }

    if (validity.rangeUnderflow) {
        return `${label} minimal ${formatConstraintValue(field.min, field.type)}.`;
    }

    if (validity.rangeOverflow) {
        return `${label} maksimal ${formatConstraintValue(field.max, field.type)}.`;
    }

    if (validity.tooShort) {
        return `${label} minimal ${field.minLength} karakter.`;
    }

    if (validity.tooLong) {
        return `${label} maksimal ${field.maxLength} karakter.`;
    }

    if (validity.stepMismatch) {
        return `Nilai ${label.toLowerCase()} tidak valid.`;
    }

    if (validity.patternMismatch) {
        return `Format ${label.toLowerCase()} tidak valid.`;
    }

    return '';
};

const resetCustomValidity = (event) => {
    const field = event.target;
    if (!field.matches?.(fieldSelectors)) {
        return;
    }

    field.setCustomValidity('');
};

const applyCustomValidity = (event) => {
    const field = event.target;
    if (!field.matches?.(fieldSelectors)) {
        return;
    }

    field.setCustomValidity('');

    const message = getCustomValidityMessage(field);
    if (message) {
        field.setCustomValidity(message);
    }
};

document.addEventListener('input', resetCustomValidity, true);
document.addEventListener('change', resetCustomValidity, true);
document.addEventListener('invalid', applyCustomValidity, true);
