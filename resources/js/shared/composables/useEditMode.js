/**
 * Composable для работы с режимом редактирования товара из корзины
 */
import { ref, onMounted } from 'vue'

export function useEditMode(calculatorId) {
    const editData = ref(null)
    const isEditMode = ref(false)

    /**
     * Проверяет наличие данных для редактирования в localStorage
     * Работает только если в URL есть параметр ?edit=1
     */
    const checkEditMode = () => {
        try {
            // Проверяем наличие параметра edit=1 в URL
            const urlParams = new URLSearchParams(window.location.search)
            const isEditParam = urlParams.get('edit') === '1'

            if (!isEditParam) {
                // Если перешли не из корзины, очищаем старые данные
                localStorage.removeItem('psp_edit_item')
                return null
            }

            const stored = localStorage.getItem('psp_edit_item')
            if (stored) {
                const data = JSON.parse(stored)
                // Проверяем, что это товар для текущего калькулятора
                if (data.calculator_id === calculatorId) {
                    editData.value = data
                    isEditMode.value = true

                    // Убираем параметр edit из URL (без перезагрузки страницы)
                    urlParams.delete('edit')
                    const newUrl = window.location.pathname + (urlParams.toString() ? '?' + urlParams.toString() : '')
                    window.history.replaceState({}, '', newUrl)

                    return data
                }
            }
        } catch (error) {
            console.error('Error loading edit data:', error)
        }
        return null
    }

    /**
     * Восстанавливает параметры калькулятора из сохраненных данных
     * @param {Object} calculatorData - реактивный объект с данными калькулятора
     * @param {Object} rawMatSelectParams - ref с материалами (для обновления если нужно)
     */
    const restoreParams = (calculatorData, rawMatSelectParams = null) => {
        if (!editData.value || !editData.value.params) {
            return false
        }

        console.log('Restoring calculator params from edit mode:', editData.value)

        // Восстанавливаем текстовое поле (для калькуляторов с буквами)
        if (editData.value.text !== undefined && calculatorData.text !== undefined) {
            calculatorData.text = editData.value.text || ''
        }

        // Восстанавливаем обычные параметры
        editData.value.params.forEach(param => {
            const { variable, type, value } = param

            // Пропускаем если такого поля нет в калькуляторе
            if (calculatorData[variable] === undefined) {
                return
            }

            // Восстанавливаем значение в зависимости от типа
            switch (type) {
                case 1: // Числовые параметры (w, h, num и т.д.)
                    calculatorData[variable] = parseFloat(value) || value
                    break

                case 2: // Булевые параметры (tape, drills и т.д.)
                    calculatorData[variable] = !!value
                    break

                case 3: // Материалы (mat_select_in3 и т.д.)
                    calculatorData[variable] = value
                    break

                case 4: // Текстовые параметры
                    calculatorData[variable] = value || ''
                    break

                default:
                    calculatorData[variable] = value
            }
        })

        // Восстанавливаем материалы (mat_select_params) если они есть
        if (editData.value.mat_select_params && rawMatSelectParams) {
            // Находим выбранные материалы и устанавливаем их ID
            editData.value.mat_select_params.forEach(savedMat => {
                if (calculatorData[savedMat.variable] !== undefined) {
                    // Берем ID материала из сохраненных данных
                    const materialId = savedMat.id || (savedMat.materials && savedMat.materials[0]?.id)
                    if (materialId) {
                        calculatorData[savedMat.variable] = materialId
                    }
                }
            })
        }

        console.log('Restored calculator data:', { ...calculatorData })

        // Очищаем данные из localStorage после успешного восстановления
        localStorage.removeItem('psp_edit_item')

        return true
    }

    /**
     * Очищает данные редактирования
     */
    const clearEditMode = () => {
        localStorage.removeItem('psp_edit_item')
        editData.value = null
        isEditMode.value = false
    }

    // Проверяем режим редактирования при монтировании
    onMounted(() => {
        checkEditMode()
    })

    return {
        editData,
        isEditMode,
        checkEditMode,
        restoreParams,
        clearEditMode
    }
}
