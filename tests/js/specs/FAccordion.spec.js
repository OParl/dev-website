import FAccordion from '@/modules/faccordion.vue';

describe('FAccordion.vue', () => {
    it('returns data as a function', () => {
        expect(typeof FAccordion.data === 'function');
    });
});
