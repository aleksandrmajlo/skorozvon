<div class="alert alert-success d-none" id="successAlert" role="alert">
    Контакт обновлен
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="thead-dark">
        <tr>
            <th scope="col"></th>
            <th scope="col">СВЕДЕНИЯ</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>ID</td>
            <td>{{$contact->idbank}}</td>
        </tr>
        <tr>
            <td>INN</td>
            <td>
                <div class="realData"><span>{{$contact->inn}}</span>
                    <a class="changeLink" href="#"><i class="fab fa-stack-exchange"></i></a>
                </div>
                <div class="resetData   align-items-center justify-content-between d-none">
                    <input class="form-control dataInp" type="text" data-orig="{{$contact->inn}}"
                           value="{{$contact->inn}}" name="inn">
                    <a class="resetLink" href="#"><i class="far fa-window-close"></i></a>
                </div>
            </td>
        </tr>
        <tr>
            <td>ORGANIZATION</td>
            <td>
                <div class="realData"><span>{{$contact->organization}}</span>
                    <a class="changeLink" href="#"><i class="fab fa-stack-exchange"></i></a>
                </div>
                <div class="resetData   align-items-center justify-content-between d-none">
                    <textarea class="form-control dataInp" type="text" data-orig="{{$contact->organization}}"
                              value="{{$contact->organization}}"
                              name="organization">{{$contact->organization}}</textarea>
                    <a class="resetLink" href="#"><i class="far fa-window-close"></i></a>
                </div>
            </td>
        </tr>
        <tr>
            <td>FULLNAME</td>
            <td>
                <div class="realData"><span> {{$contact->fullname}}</span>
                    <a class="changeLink" href="#"><i class="fab fa-stack-exchange"></i></a>
                </div>
                <div class="resetData   align-items-center justify-content-between d-none">
                    <textarea class="form-control dataInp" type="text" data-orig="{{$contact->fullname}}"
                              value="{{$contact->fullname}}" name="fullname">{{$contact->fullname}}</textarea>
                    <a class="resetLink" href="#"><i class="far fa-window-close"></i></a>
                </div>
            </td>
        </tr>
        <tr>
            <td>PHONE</td>
            <td>
                <div class="realData"><span>{{$contact->phone}}</span>
                    <a class="changeLink" href="#"><i class="fab fa-stack-exchange"></i></a>
                </div>
                <div class="resetData   align-items-center justify-content-between d-none">
                    <input class="form-control dataInp" type="tel" data-orig="{{$contact->phone}}"
                           value="{{$contact->phone}}" name="phone">
                    <a class="resetLink" href="#"><i class="far fa-window-close"></i></a>
                </div>
            </td>
        </tr>
        <tr>
            <td>EMAIL</td>
            <td>
                <div class="realData"><span>{{$contact->email}}</span>
                    <a class="changeLink" href="#"><i class="fab fa-stack-exchange"></i></a>
                </div>
                <div class="resetData   align-items-center justify-content-between d-none">
                    <input class="form-control dataInp" type="email" data-orig="{{$contact->email}}"
                           value="{{$contact->email}}" name="email">
                    <a class="resetLink" href="#"><i class="far fa-window-close"></i></a>
                </div>
            </td>
        </tr>
        <tr>
            <td>ADDRESS</td>
            <td>
                <div class="realData"><span>{{$contact->address}}</span>
                    <a class="changeLink" href="#"><i class="fab fa-stack-exchange"></i></a>
                </div>
                <div class="resetData   align-items-center justify-content-between d-none">
                    <textarea class="form-control dataInp" type="text" data-orig="{{$contact->address}}"
                              value="{{$contact->address}}" name="address">{{$contact->address}}</textarea>
                    <a class="resetLink" href="#"><i class="far fa-window-close"></i></a>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<button disabled id="saveClient" class="btn btn-primary " data-id="{{$contact->id}}">
    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
    <span>Сохранить измененные данные контакта</span>
</button>
<div class="table-responsive mt-5">
    <table class="table table-bordered table-hover">
        <thead class="thead-dark">
        <tr>
            <td>Банк</td>
            <td>Статус</td>
            <td>CityCode</td>
            <td>ProductCode</td>
            <td></td>
            <td></td>
        </tr>
        </thead>
        <tbody>
        @foreach($banks as $bank)

            @if(isset($user_id)&&$bank->users->contains($user_id))
                <tr class="oneBank ">
                    <td>{{$bank->name}}</td>
                    <td class="text-center">
                        @include('contacts.onebank')
                    </td>
                    <td>
                        <select name="city" class="bank_city_{{$bank->id}}" data-id="{{$bank->id}}">
                            <option value="-1">Выбирите город</option>
                            @if($bank->cities)
                                @foreach($bank->cities as $city)
                                    <option value="{{$city->idd}}">{{$city->title}}</option>
                                @endforeach
                            @endif
                        </select>
                    </td>
                    <td>
                        <select name="tariff" class="bank_tariff_{{$bank->id}}" data-id="{{$bank->id}}">
                            <option value="-1">Тип продукта</option>
                            @if($bank->tariffs)
                                @foreach($bank->tariffs as $tariff)
                                    <option value="{{$tariff->idd}}">{{$tariff->title}}</option>
                                @endforeach
                            @endif
                        </select>
                    </td>
                    <td>
                      <textarea placeholder="Cообщение" name="mes_{{$bank->id}}" class="form-control"></textarea>
                    </td>
                    <td>
                        @if($bank_data[$bank->id]['value']===0)
                            <button
                                class="send_bank button_{{$bank->id}}"
                                data-contact_id="{{$contact->id}}"
                                data-id="{{$bank->id}}"
                                disabled class="btn btn-default">ОТПРАВИТЬ</button>
                        @endif
                    </td>
                </tr>
            @elseif(!isset($user_id))
                <tr class="oneBank ">
                    <td>{{$bank->name}}</td>
                    <td class="text-center">
                        @include('contacts.onebank')
                    </td>

                    <td>
                        <select name="city" class="bank_city_{{$bank->id}}" data-id="{{$bank->id}}">
                            <option value="-1">Выбирите город</option>
                            @if($bank->cities)
                                @foreach($bank->cities as $city)
                                    <option value="{{$city->idd}}">{{$city->title}}</option>
                                @endforeach
                            @endif
                        </select>
                    </td>
                    <td>
                        <select name="tariff" class="bank_tariff_{{$bank->id}}" data-id="{{$bank->id}}">
                            <option value="-1">Тип продукта</option>
                            @if($bank->tariffs)
                                @foreach($bank->tariffs as $tariff)
                                    <option value="{{$tariff->idd}}">{{$tariff->title}}</option>
                                @endforeach
                            @endif
                        </select>
                    </td>
                    <td>
                        <textarea placeholder="Cообщение" name="mes_{{$bank->id}}" class="form-control"></textarea>
                    </td>
                    <td>
                        @if($bank_data[$bank->id]['value']===0)
                            <button
                                class="send_bank button_{{$bank->id}}"
                                data-contact_id="{{$contact->id}}"
                                data-id="{{$bank->id}}"
                                disabled class="btn btn-default">ОТПРАВИТЬ
                            </button>
                        @endif
                    </td>
                </tr>
            @endif
        @endforeach
        </tbody>

    </table>
</div>
<contact-bank contact_id="{{$contact->id}}"></contact-bank>
<a  href="/statusreport" id="get_result_report" class="d-none">Получить результат </a>

