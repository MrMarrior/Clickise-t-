<template>
    <div class="container vh-100 d-flex justify-content-center align-items-center">
        <div class="row ">
            <div>
                <div class="input-group mb-3">
                    <input ref="nameInput"  type="text" class="form-control" @input='userName = $event.target.value'
                           v-bind:value="userName" placeholder="Введите имя" aria-label="Введите имя"
                           aria-describedby="button-addon2">
                    <button class="btn btn-outline-secondary" type="button" id="button-addon2" @click="selectConfirm()">Изменить</button>
                </div>
                <div class="input-group mb-3">
                    <input @input='userEmail = $event.target.value' v-bind:value="userEmail" ref="emailInput"
                           type="email" class='form-control' placeholder="Введил новый email"
                           aria-label="Введил новый email" aria-describedby="button-addon2">
                    <button class="btn btn-outline-secondary" type="button" id="button-addon2" @click="changeEmail">
                        Изменить
                    </button>
                </div>
                <div v-if="!isEditing" class="d-grid gap-2 col-6 ">
                    <button class="btn btn-secondary" type="button" @click="showPasswordChangeField">Изменить пароль</button>
                </div>
                <div v-else class="input-group mb-3">
                    <input @input='password = $event.target.value'
                            ref="pasInput"
                           type="password" class='form-control' placeholder="Введите новый пароль"
                           aria-label="Введите новый пароль" aria-describedby="button-addon2">
                    <button  class="btn btn-outline-secondary" type="button" id="button-addon2" @click="selectConfirm()">
                        Изменить
                    </button>
                </div>
            </div>
        </div>
        <div v-if="modalWindow" class="d-flex vh-100 vw-100 custom-background justify-content-center align-items-center">
            <div  class="w-25 h-25 custom-container ">
                <div class="text-center lead ">  Пожалуйста выберите способ отправки кода с подтверждением (по умолчанию 123)</div>
            <div v-if="!waitingCode" class='icons-container row mt-4 '>
<!--                Хотел вставить сюда иконки для выбора вместо кнопок, но не понял как адекватно передать asset('storage/public/...') во Vue компонент-->
                <button class="col btn btn-info mx-2" @click="codeDelivery('Telegram')"> Telegram </button>
                <button class="col btn btn-info mx-2" @click="codeDelivery('SMS')"> SMS </button>
                <button class="col btn btn-info mx-2" @click="codeDelivery('Email')"> Email </button>
            </div>
            <div v-else class=" mt-4 row justify-content-center icons-container">
                <input ref="codeInput" class='form-control' placeholder="Введите код"
                       aria-label="Введите код (123)" aria-describedby="button-addon2">
                <button class="btn btn-warning m-1" @click="codeValidate()">Подтвердить</button>
            </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            userId: null,
            userName: '',
            password: '',
            userEmail: '',
            isEditing: false,
            modalWindow: false,
            waitingCode: false,
            emailPattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
            settings: [
                {title: 'Изменить имя'},
                {title: 'Изменить пароль'},  // Массив значений, на потенциальное добавление новых пунктов настроек
                {title: 'Изменить почту'}
            ]
        };
    },
    methods: {
        async codeDelivery(typeOfConfirm) {
            try {
                const data = {
                    userId: this.userId,
                    confirmation: typeOfConfirm ,
                };
                const response = await axios.post('/settings/sendCode', data);
                this.responseData = response.data;
                this.waitingCode = true
                this.code = this.responseData.code
            } catch (err) {
                this.error = 'Произошла ошибка: ' + err.message;
            }
        },
        async changeSetting() {
            try {
                const data = {
                    userId: this.userId,
                    name: this.userName,
                    email: this.userEmail,
                    password: this.password
                };
                const response = await axios.post('/settings/changeSettings', data);
                this.modalWindow = false
                this.waitingCode = false
            } catch (err) {
                this.error = 'Произошла ошибка: ' + err.message;
            }
        },
        codeValidate(){
            if (this.$refs.codeInput.value !== this.code){
                this.$refs.codeInput.className = 'form-control is-invalid'
            }
            else {
                this.changeSetting()
            }
        },
        selectConfirm(){
            if (this.emailPattern.test(this.userEmail)){
            this.modalWindow = true
            }
            else{
                this.$refs.emailInput.className = 'form-control is-invalid'
                alert('Email введен неверно')
            }
        },
        showPasswordChangeField() {
            this.isEditing = true;
        },
        changeEmail() {
            if (this.emailPattern.test(this.userEmail)) {
                this.$refs.emailInput.className = 'form-control'
                this.selectConfirm()
            } else {
                this.$refs.emailInput.className = 'form-control is-invalid'
            }
        },
    },
    async created() {
        try {
            const response = await axios.get('/getUserData');
            this.userName = response.data.name;
            this.password = response.data.password;
            this.userEmail = response.data.email;
            this.userId = response.data.id;

        } catch (error) {
            this.error = 'Ошибка при загрузке данных: ' + error.message;
        }
    },
};
</script>

<style>
.custom-container {
    position: fixed;
    z-index: 7;
    background-color: #e8e8e8;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}
.custom-background{
    position: fixed;
    z-index: 6;
    background-color: rgba(0, 0, 0, 0.61);
}
.icons-container{
    position: relative;
    z-index: 6;
}
</style>
