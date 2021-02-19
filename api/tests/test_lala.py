import requests
from pytest import approx


API_BASE_URL = 'http://localhost:9002'


def test_get_facility_by_name():
    response = requests.get(f'{API_BASE_URL}/facility?name=Feldmoching')

    assert response.status_code == 200
    assert response.headers['Content-Type'] == 'application/json; charset=utf-8'

    response_body = response.json()

    assert len(response_body) == 2

    assert response_body[0]["lat"] == approx(11.5410187, rel=1e-5)
    assert response_body[0]["lon"] == approx(48.213790699886196, rel=1e-5)
    assert response_body[0]["name"] == "Feldmoching"
    assert response_body[0]["uicname"] is None
    assert response_body[0]["uicref"] is None
    assert response_body[0]["ref"] is None
    assert response_body[0]["id"] == "3189921161"
    assert response_body[0]["type"] == "station"
    assert response_body[0]["operator"] is None
    assert response_body[0]["stationcategory"] is None

    assert response_body[1]["lat"] == approx(11.541275300000001, rel=1e-5)
    assert response_body[1]["lon"] == approx(48.213803599886198, rel=1e-5)
    assert response_body[1]["name"] == "Feldmoching"
    assert response_body[1]["uicname"] is None
    assert response_body[1]["uicref"] == "8004147"
    assert response_body[1]["ref"] == "MFE"
    assert response_body[1]["id"] == "2499552238"
    assert response_body[1]["type"] == "station"
    assert response_body[1]["operator"] == "DB Netz AG"
    assert response_body[1]["stationcategory"] is None


def test_get_facility_by_ref():
    response = requests.get(f'{API_BASE_URL}/facility?ref=MHRK')

    assert response.status_code == 200
    assert response.headers['Content-Type'] == 'application/json; charset=utf-8'

    response_body = response.json()

    assert len(response_body) == 1

    assert response_body[0]["lat"] == approx(11.5096112, rel=1e-5)
    assert response_body[0]["lon"] == approx(48.043303899927402, rel=1e-5)
    assert response_body[0]["name"] == "Höllriegelskreuth"
    assert response_body[0]["uicname"] is None
    assert response_body[0]["uicref"] == "8002899"
    assert response_body[0]["ref"] == "MHRK"
    assert response_body[0]["id"] == "2514399802"
    assert response_body[0]["type"] == "station"
    assert response_body[0]["operator"] == "DB Netz AG"
    assert response_body[0]["stationcategory"] == "6"
