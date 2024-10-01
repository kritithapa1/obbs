from flask import Flask, request, jsonify
import pandas as pd
import joblib
from flask_cors import CORS


app = Flask(__name__)
CORS(app)

# Load the pre-trained model
model = joblib.load('banquet_booking_model.joblib')

# Create a function to generate one-hot encoding for the Month_day feature
def one_hot_encode_date(date_str, unique_month_days):
    # Convert the given date to datetime
    date_obj = pd.to_datetime(date_str)
    month_day_str = f"{date_obj.month:02}:{date_obj.day:02}"  # Format as MM:DD

    # Create a DataFrame with one-hot encoding
    one_hot = pd.get_dummies(pd.Series([month_day_str]), prefix='Month_day', dtype=int)

    # Reindex to include all unique month_day combinations
    one_hot = one_hot.reindex(columns=unique_month_days, fill_value=0)

    return one_hot.values

def load_unique_month_days():
    # Load the unique month_day values from the model training dataset
    data = pd.read_csv('banquet_bookings_dataset.csv')
    data['Date'] = pd.to_datetime(data['Date'])
    data['Month'] = data['Date'].dt.month.astype(str).str.zfill(2)
    data['Day'] = data['Date'].dt.day.astype(str).str.zfill(2)
    data['Month_day'] = data['Month'] + ':' + data['Day']
    unique_month_days = pd.get_dummies(data['Month_day'], prefix='Month_day').columns.tolist()
    return unique_month_days

unique_month_days = load_unique_month_days()

def predict_bookings(given_date):
    # Generate one-hot encoded features for the given date
    one_hot_features = one_hot_encode_date(given_date, unique_month_days)

    # Make a prediction using the model
    predicted_booking = model.predict(one_hot_features)

    return predicted_booking[0]

@app.route('/predict', methods=['GET'])
def predict():
    # Get the 'date' parameter from the query string
    given_date = request.args.get('date')
    
    if not given_date:
        return jsonify({'error': 'Please provide a date as a query parameter, e.g., /predict?date=YYYY-MM-DD'}), 400
    
    try:
        prediction = predict_bookings(given_date)
        if prediction>2:
            res="This day is high on demand. Confirm your Booking ASAP."
        else:
            res="This day is low on demand."

        # return jsonify({'date': given_date, 'predicted_bookings': int(prediction)})
        return jsonify({'date': given_date,'response': res})
    except Exception as e:
        return jsonify({'error': str(e)}), 500

if __name__ == '__main__':
    app.run(debug=True)
