# <setup>
from yahooquery import Ticker
from datetime import datetime
import statistics
import csv
import json
# </setup>

with open('stock_tickers.csv') as stock_tickers_csv_file:
    readCSV = csv.reader(stock_tickers_csv_file, delimiter=',')
    good_stocks = {
        "date": datetime.now().strftime("%d/%m/%Y"),
        "good_stocks": {}
    }
    for row in readCSV:
        # <data_colection>
        try:
            stock = row[0]
        except:
            continue
        try:
            data = Ticker(stock.strip()).all_modules[stock.strip()]
        except:
            continue
        try:
            mostRecentQuarter_string = data['defaultKeyStatistics']['mostRecentQuarter']
            mostRecentQuarter = datetime.strptime(mostRecentQuarter_string, '%Y-%m-%d %H:%M:%S')
        except:
            try:
                mostRecentQuarter = datetime.strptime(mostRecentQuarter_string, '%Y-%m-%d')
            except:
                continue
        try:
            lastReportedYear = mostRecentQuarter.year
        except:
            continue
        try:
            longName = data['price']['longName'].replace(",", '')
            print("Analyzing %s" % longName)
            currentPrice = data['financialData']['currentPrice']
            sharesOutstanding = data['defaultKeyStatistics']['sharesOutstanding']
            marketCap = data['price']['marketCap']
            exchangeName = data['price']['exchangeName']
            currency = data['price']['currency']
            netIncome_perShare = []
            netIncome_perShare.append(data['defaultKeyStatistics']['trailingEps'])
            netIncome_perShare.append(data['incomeStatementHistory']['incomeStatementHistory'][0]['netIncome'] / sharesOutstanding)
            netIncome_perShare.append(data['incomeStatementHistory']['incomeStatementHistory'][1]['netIncome'] / sharesOutstanding)
            netIncome_perShare.append(data['incomeStatementHistory']['incomeStatementHistory'][2]['netIncome'] / sharesOutstanding)
            netIncome_perShare.append(data['incomeStatementHistory']['incomeStatementHistory'][3]['netIncome'] / sharesOutstanding)
            total_Liabilities = data['balanceSheetHistoryQuarterly']['balanceSheetStatements'][0]['totalLiab']
            average_netIncome_perShare = statistics.mean(netIncome_perShare)
            trailing_PE = currentPrice / netIncome_perShare[0]
            debt_to_income_ratio = total_Liabilities / average_netIncome_perShare        
        except:
            continue
        # </data_colection>

        # <stock_screening>

        try:
            if not (average_netIncome_perShare > 0):
                continue
            if not (
                netIncome_perShare[0] > 0 and
                netIncome_perShare[1] > 0 and
                netIncome_perShare[2] > 0 and
                netIncome_perShare[3] > 0 and
                netIncome_perShare[4] > 0
            ):
                continue
            if not (
                netIncome_perShare[0] / average_netIncome_perShare >= 0.75
                and netIncome_perShare[0] / average_netIncome_perShare <= 1.5
                and netIncome_perShare[1] / average_netIncome_perShare >= 0.75
                and netIncome_perShare[1] / average_netIncome_perShare <= 1.5
                and netIncome_perShare[2] / average_netIncome_perShare >= 0.75
                and netIncome_perShare[2] / average_netIncome_perShare <= 1.5
                and netIncome_perShare[3] / average_netIncome_perShare >= 0.75
                and netIncome_perShare[3] / average_netIncome_perShare <= 1.5
                and netIncome_perShare[4] / average_netIncome_perShare >= 0.75
                and netIncome_perShare[4] / average_netIncome_perShare <= 1.5
            ):
                continue
            if not (trailing_PE <= 20 and debt_to_income_ratio <= 10):
                continue
            good_stocks["good_stocks"].update({
                stock: {
                    "Ticker": stock,
                    "LongName": longName,
                    "Price": currentPrice,
                    "Exchange": exchangeName,
                    "AvgEPS":round(average_netIncome_perShare,2),
                    "TrailingEPS": round(netIncome_perShare[0],2),
                    "TrailingPE": round(trailing_PE,2),
                    "Currency": currency
                }
            })
        except:
            continue
        # </stock_screening>

    # <export>
    with open("stock_data.json", "w") as outfile:  
        json.dump(good_stocks, outfile) 
    # <export>