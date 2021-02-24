import requests
from lxml import etree
from io import BytesIO

WEB_BASE_URL = 'http://www.openrailwaymap.org'


def test_get_index(snapshot):
    response = requests.get(f'{WEB_BASE_URL}/')

    assert response.status_code == 200

    parser = etree.HTMLParser()
    tree = etree.parse(BytesIO(response.content), parser)
    result = etree.tostring(tree.getroot(), pretty_print=True, method='html')

    assert result == snapshot
