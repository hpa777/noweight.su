<template>
    <form method="post" class="form__body" v-on:submit.prevent="submit" v-if="!success">
        <div class="form__title">Рассчитать праздник</div>
        <ul class="master">
            <li class="master__item" v-bind:class="{active: this.activeScreen === 1}">
                <div class="form__row row-fl jcsb">
                    <div class="form__group form__col">
                        <label class="form__group__label">Дата мероприятия</label>
                        <div class="select-wrapper">
                            <input v-datepicker v-on:change="dateChange"
                                @focus="dataHasFocus = true"
                                @blur="dataHasFocus = false"
                                v-model="model.date" type="text" class="input-field" autocomplete="off">
                        </div>
                        <div class="form__group__mes"></div>
                    </div>
                    <div class="form__group form__col">
                        <label class="form__group__label">Введите количество участников</label>
                        <input type="number" class="input-field" v-model="model.numVisitors">
                        <div class="form__group__mes yellow">Для расчёта стоимости посещения</div>
                    </div>
                </div>
                <div class="form__row row-fl jcsb">
                    <div class="form__group form__col">
                        <label class="form__group__label">Выберите зону посещения</label>
                        <div class="select-wrapper">
                            <select class="input-field" v-model="model.place" v-on:change="placeChange">                                        
                                <option v-for="item in data.svp" :key="item.place">{{item.place}}</option>                            
                            </select>
                        </div>
                        <div class="form__group__mes"></div>
                    </div>
                    <div class="form__group form__col" v-if="model.date && model.place">
                        <label class="form__group__label">Продолжительность посещения</label>
                        <div class="select-wrapper">
                            <select class="input-field" v-model="model.price" v-on:change="timeChange">                                        
                                <option v-for="item in time" :key="item.time" 
                                    v-bind:value="item">{{item.time}}</option>                            
                            </select>
                        </div>
                        <div class="form__group__mes"></div>
                    </div>
                </div>
                
                <div class="schedule__pagi row-fl">                            
                    <button type="button" class="schedule__button row-fl aic master__next" 
                        v-if="model.numVisitors && model.price" v-on:click="changeScreen('next')">                        
                        <span>Далее</span>
                        <svg><use xlink:href="/images/sprite.svg#arrow"></use></svg>
                    </button>
                </div>
            </li>
            <li class="master__item" v-bind:class="{active: this.activeScreen === 2}">
                <div class="form__message mb--11">Выбрать аниматора вы можете <a href="/prazdniki.html">здесь</a></div>
                <div class="form__row row-fl jcsb">
                    <div class="form__group form__col">
                        <label class="form__group__label">Нужен аниматор?</label>
                        <div class="select-wrapper">
                            <select class="input-field" v-model="model.animator">
                                <option value="none">Нет</option>
                                <option v-for="item in data.hdp" :key="item.name"
                                    v-bind:value="item">{{item.name}}</option>                            
                            </select>
                        </div>
                        <div class="form__group__mes"></div>
                    </div>
                    <div class="form__group form__col" v-if="model.animator && model.animator != 'none'">
                        <div class="form__message form__message--center yellow" style="padding-top:1.5em">{{model.animator.txt}}</div>
                    </div>                                                        
                </div>
                <div class="form__row row-fl jcsb" v-if="model.animator && model.animator != 'none'">
                    <div class="form__group form__col">
                        <label class="form__group__label">Количество аниматоров</label>
                        <div class="select-wrapper">
                            <select class="input-field" v-model="model.numAnimators">                                        
                                <option v-if="model.numVisitors < 13">1</option>
                                <option>2</option>
                                <option>3</option>                                        
                            </select>
                        </div>
                        <div class="form__group__mes yellow">
                            <p>Рассчитывается исходя из количества участников</p>
                            <p>до 12 участников - 1 аниматор</p>
                            <p>от 12 до 24 - 2 аниматора</p>
                        </div>
                    </div>
                    <div class="form__group form__col">
                        <label class="form__group__label">Продолжительность программы</label>
                        <div class="select-wrapper">
                            <select class="input-field" v-model="model.questTime">
                                <option v-for="item in questTime" :key="item.txt" 
                                    v-bind:value="item">{{item.txt}}</option>
                            </select>
                        </div>
                        <div class="form__group__mes yellow">Время программы с аниматором не увеличивает продолжительность выбранного тарифа на посещения</div>
                    </div>
                </div>
                <div class="schedule__pagi row-fl">
                    <button type="button" class="schedule__button row-fl aic master__prev" v-on:click="changeScreen('prev')">
                        <svg><use xlink:href="/images/sprite.svg#arrow"></use></svg>
                        <span>Назад</span>
                    </button>
                    <button type="button" class="schedule__button row-fl aic master__next" 
                        v-on:click="changeScreen('next')"
                        v-if="this.model.animator == null || this.model.animator == 'none' || (this.model.numAnimators && this.model.questTime)">                        
                        <span>Далее</span>
                        <svg><use xlink:href="/images/sprite.svg#arrow"></use></svg>
                    </button>
                </div>
            </li>
            <li class="master__item" v-bind:class="{active: this.activeScreen === 3}">
                <div class="form__col">
                    <div class="form__group">
                        <label class="form__group__label">Ваше имя*</label>
                        <input type="text" class="input-field" v-model="model.name" required>
                        <div class="form__group__mes"></div>
                    </div>
                    <div class="form__group">
                        <label class="form__group__label">Ваш телефон*</label>
                        <input type="text" class="input-field phone-mask" required v-model="model.phone"
                            data-inputmask="'mask':'+7(999)999-99-99'">
                        <div class="form__group__mes"></div>
                    </div>
                </div>
                <div class="form__row mb--11">
                    <div class="form__group">
                        <div class="checkbox row-fl aic">
                            <input id="cb6" type="checkbox" data-btnid="sendbtn6" checked>
                            <label for="cb6"></label>
                            <span>Согласие на <a href="upload/Documents/soglasie-na-obrabotku-personalnyix-dannyix-(prilozhenie-№4).pdf" target="_blank">обработку персональных данных</a></span>
                        </div>
                    </div>
                </div>
                <div class="form__row form__row--center">
                    <button type="submit" id="sendbtn6" class="button button--hover-opacity">показать стоимость</button>
                </div>
                
                <div class="schedule__pagi row-fl">
                    <button type="button" class="schedule__button row-fl aic master__prev" v-on:click="changeScreen('prev')">
                        <svg><use xlink:href="/images/sprite.svg#arrow"></use></svg>
                        <span>Назад</span>
                    </button>                
                </div>
            </li>
        </ul>
    </form>
    <div v-else class="pb--7">
        <div class="form__price">ВАШ ЗАКАЗ:</div>
        <div class="form__message mb--11">                    
            <p>Дата мероприятия: <span class="yellow">{{model.date}}</span></p>
            <p>Количество участников: <span class="yellow">{{model.numVisitors}}</span></p>
            <p>Зона посещения: <span class="yellow">{{model.place}}</span></p>
            <p v-if="model.price">Продолжительность посещения: <span class="yellow">{{model.price.time}}</span></p>
            <template v-if="model.animator && model.animator != 'none'">
                <p>Аниматор: <span class="yellow">{{model.animator.name}}</span></p>
                <p>Количество аниматоров: <span class="yellow">{{model.numAnimators}}</span></p>
                <p v-if="model.questTime">Продолжительность программы: <span class="yellow">{{model.questTime.txt}}</span></p>
            </template>
        </div>
        <div class="form__price">ориентировочная стоимость: <span class="yellow">{{model.sum}} руб.</span></div>
        <div class="form__message form__message--center">Стоимость блюд в кафе Гравитация рассчитывается отдельно,<br>
            с меню можете ознакомиться <a href="/kafe.html">здесь</a>
        </div>
        <!--div class="form__title">Спасибо, сообщение отправлено!</div>
        <div class="form__message">Hаш менеджер скоро Вам позвонит!</div-->
    </div>
</template>

<script>
export default {
    name: "calc", 
    mounted() {		
		this.setData();
	},
    data: () => ({
        activeScreen: 1,
        data: [],
        time: [],
        questTime: [],
        dayType : '',
        dataHasFocus : false,        
        success: false,
        model: {
            place : '',
            date: '',
            price: null,
            numVisitors: null,
            animator: null,
            numAnimators: null,
            questTime: null,
            name : '',
            phone : '',
            message : '',
            template: "calc",
            token: null,
            sum : null
        }
    }),
    methods: {
        async setData() {
            const self = this;
            $.get("/getprice.json", null, function(res){ //    api/GetPrice.php
                self.data = res;  
            }, 'json');                   
        },
        changeScreen(dir) {
            if(dir === "next") {
                this.activeScreen++;
            } else {
                this.activeScreen--;
            }
            if (this.activeScreen === 3) {
                let visit = this.model.price.price * this.model.numVisitors;
                if (this.model.animator && this.model.animator.price) {
                    visit+= this.model.animator.price * this.model.numAnimators * this.model.questTime.val;
                }
                this.model.sum = visit;
            }
        },
        placeChange() {
            if (this.model.place && this.dayType) {
                this.time = this.data.svp.find(item => item.place === this.model.place).prices[this.dayType];
            }            
        },
        dateChange() {
            if (!this.dataHasFocus) {
                const self = this;
                $.get('/api/DayCheck.php', {
                    'date': this.model.date
                }, function(res) {
                    self.dayType = res;
                    self.placeChange(null);
                });         
            }                        
        },
        timeChange() {
            if (this.model.price) {                
                let r = [];
                for (let index = 1; index <= this.model.price.tn; index++) {
                    let ts = "часа";
                    if (index == 1) {
                        ts = "час";
                    } else if (index > 4) {
                        ts = "часов";
                    }
                    r.push({
                        txt : `${index} ${ts}`,
                        val : index
                    });                   
                }                
                this.questTime = r;
            }
        },
        submit() {
            const self = this;
            //self.success = true;
            
            grecaptcha.ready(function () {
                grecaptcha.execute(reCAPTCHA_site_key, { action: 'submit' }).then(function (token) {
                    self.model.token = token;
                    $.post("/send.json", self.model, function(resp){
                        self.success = resp.status == "ok";                        
                    }, "json");
                });
            });
                  
        }
    }     
}
</script>